<input type="hidden" name="teacher_id" value="{{ !empty($teacher->id) ? $teacher->id : '' }}">
<div class="mb-3">
    <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
    <input type="text" name="name" id="name" class="form-control" value="{{ !empty($teacher->name) ? $teacher->name : '' }}">
</div>
<div class="mb-3">
    <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
    <input type="email" name="email" id="email" class="form-control" value="{{ !empty($teacher->email) ? $teacher->email : '' }}">
</div>
<div class="mb-3">
    <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
    <input type="password" name="password" id="password" class="form-control" >
</div>
<div class="mb-3">
    <label for="password" class="form-label">Confirm Password<span class="text-danger">*</span></label>
    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
</div>
<div class="mb-3">
    <label for="phone" class="form-label">Phone</label>
    <input type="text" name="phone" id="phone" class="form-control" value="{{ !empty($teacher->phone) ? $teacher->phone : '' }}">
</div>
<div class="mb-3">
    <label for="subject" class="form-label">Subject</label>
    <input type="text" name="subject" id="subject" class="form-control" value="{{ !empty($teacher->subject) ? $teacher->subject : '' }}">
</div>
<div class="mb-3">
    <label for="bio" class="form-label">Bio</label>
    <textarea name="bio" id="bio" class="form-control" rows="4">{{ !empty($teacher->bio) ? $teacher->bio : null }}</textarea>
</div>
<div class="mb-3">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>