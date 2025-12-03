<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClassController extends Controller
{
    /**
     * Display classes page
     */
    public function index()
    {
        $classes = Classes::with(['teachers', 'students'])
            ->where('is_archived', false)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.classes', compact('classes'));
    }

    /**
     * Get classes (AJAX)
     */
    public function getClasses(Request $request)
    {
        $query = Classes::with(['teachers', 'students']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('class_code', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('room', 'like', "%{$search}%");
            });
        }

        // Filter by subject
        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->where('academic_year', $request->year);
        }

        // Filter by semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Filter by status
        if ($request->filled('status')) {
            $isArchived = $request->status === 'archived';
            $query->where('is_archived', $isArchived);
        }

        $classes = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $classes
        ]);
    }

    /**
     * Create new class
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'subject' => 'required|string|max:100',
            'academic_year' => 'required|string|max:20',
            'semester' => 'nullable|string|max:50',
            'schedule' => 'nullable|string|max:100',
            'room' => 'nullable|string|max:100',
            'max_students' => 'nullable|integer|min:1|max:500',
            'credits' => 'nullable|numeric|min:0|max:10',
            'description' => 'nullable|string|max:1000',
        ]);

        // Generate unique class code
        $classCode = $this->generateClassCode();

        // Create class
        $class = Classes::create([
            'name' => $validated['name'],
            'subject' => $validated['subject'],
            'academic_year' => $validated['academic_year'],
            'semester' => $validated['semester'] ?? null,
            'schedule' => $validated['schedule'] ?? null,
            'room' => $validated['room'] ?? null,
            'max_students' => $validated['max_students'] ?? null,
            'credits' => $validated['credits'] ?? null,
            'description' => $validated['description'] ?? null,
            'class_code' => $classCode,
            'qr_code' => $classCode,
            'created_by_admin_id' => auth()->id(),
            'is_archived' => false,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Class created successfully!',
                'data' => [
                    'id' => $class->id,
                    'name' => $class->name,
                    'subject' => $class->subject,
                    'class_code' => $class->class_code,
                    'academic_year' => $class->academic_year,
                    'semester' => $class->semester,
                    'schedule' => $class->schedule,
                    'room' => $class->room,
                    'max_students' => $class->max_students,
                    'credits' => $class->credits,
                ]
            ]);
        }

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class created successfully!')
            ->with('class_code', $class->class_code);
    }

    /**
     * Update class
     */
    public function update(Request $request, $id)
    {
        $class = Classes::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'subject' => 'sometimes|string|max:100',
            'academic_year' => 'sometimes|string|max:20',
            'semester' => 'nullable|string|max:50',
            'schedule' => 'nullable|string|max:100',
            'room' => 'nullable|string|max:100',
            'max_students' => 'nullable|integer|min:1|max:500',
            'credits' => 'nullable|numeric|min:0|max:10',
            'description' => 'nullable|string|max:1000',
        ]);

        $class->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Class updated successfully',
                'data' => $class
            ]);
        }

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class updated successfully!');
    }

    /**
     * Archive/Unarchive class
     */
    public function toggleArchive($id)
    {
        $class = Classes::findOrFail($id);
        $class->update(['is_archived' => !$class->is_archived]);

        return response()->json([
            'success' => true,
            'message' => $class->is_archived ? 'Class archived' : 'Class restored',
            'data' => ['is_archived' => $class->is_archived]
        ]);
    }

    /**
     * Delete class
     */
    public function destroy($id)
    {
        $class = Classes::findOrFail($id);
        $class->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Class deleted successfully'
            ]);
        }

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class deleted successfully!');
    }

    /**
     * Reset class code
     */
    public function resetClassCode($id)
    {
        $class = Classes::findOrFail($id);
        $oldCode = $class->class_code;
        $newCode = $this->generateClassCode();

        $class->update([
            'class_code' => $newCode,
            'qr_code' => $newCode
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Class code reset successfully',
            'data' => [
                'old_code' => $oldCode,
                'new_code' => $newCode
            ]
        ]);
    }

    /**
     * Get class statistics
     */
    public function getClassStats($id)
    {
        $class = Classes::with(['students', 'teachers'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'total_students' => $class->students()->count(),
                'total_teachers' => $class->teachers()->count(),
                'max_students' => $class->max_students,
                'available_seats' => $class->getAvailableSeats(),
                'enrollment_percentage' => $class->getEnrollmentPercentage(),
                'is_full' => $class->isFull(),
                'can_enroll' => $class->canEnroll(),
            ]
        ]);
    }

    /**
     * Generate unique 8-character class code
     */
    private function generateClassCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Classes::where('class_code', $code)->exists());

        return $code;
    }
}
