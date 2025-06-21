<input type="hidden" name="student_id" value="{{ !empty($student->id) ? $student->id : '' }}">

<div class="mb-3">
    <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
    <input type="text" name="name" id="name" class="form-control" value="{{ !empty($student->name) ? $student->name : '' }}">
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
    <input type="email" name="email" id="email" class="form-control" value="{{ !empty($student->email) ? $student->email : '' }}">
</div>

<div class="mb-3">
    <label for="phone" class="form-label">Phone</label>
    <input type="text" name="phone" id="phone" class="form-control" value="{{ !empty($student->phone) ? $student->phone : '' }}">
</div>

<div class="mb-3">
    <label for="address" class="form-label">Address</label>
    <input type="text" name="address" id="address" class="form-control" value="{{ !empty($student->address) ? $student->address : '' }}">
</div>

<div class="mb-3">
    <label for="class" class="form-label">Class</label>
    <input type="text" name="class" id="class" class="form-control" value="{{ !empty($student->class) ? $student->class : '' }}">
</div>

<div class="mb-3">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>
