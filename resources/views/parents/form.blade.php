<input type="hidden" name="parent_id" value="{{ !empty($parent->id) ? $parent->id : '' }}">

<div class="mb-3">
    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" id="name" class="form-control" value="{{ !empty($parent->name) ? $parent->name : '' }}" >
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
    <input type="email" name="email" id="email" class="form-control" value="{{ !empty($parent->email) ? $parent->email : '' }}" >
</div>

<div class="mb-3">
    <label for="phone" class="form-label">Phone</label>
    <input type="text" name="phone" id="phone" class="form-control" value="{{ !empty($parent->phone) ? $parent->phone : '' }}">
</div>

<div class="mb-3">
    <label for="occupation" class="form-label">Occupation</label>
    <input type="text" name="occupation" id="occupation" class="form-control" value="{{ !empty($parent->occupation) ? $parent->occupation : '' }}">
</div>

<div class="mb-3">
    <label for="student_id" class="form-label">Student</label>
    <select name="student_id" id="student_id" class="form-control">
        <option value=""> Select Student </option>
        @foreach($students as $student)
            <option value="{{ $student->id }}"
                {{ (old('student_id', $parent->student_id ?? '') == $student->id) ? 'selected' : '' }}>
                {{ $student->name }}
            </option>
        @endforeach
    </select>
    @error('student_id') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>

