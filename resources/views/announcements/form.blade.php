<div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" class="form-control" id="title" name="title" placeholder="Please enter title" value="{{ !empty($announcement->title) ? $announcement->title : "" }}">
</div>
<div class="mb-3">
    <label for="content" class="form-label">Content</label>
    <textarea class="form-control" id="content" name="content" rows="4" placeholder="Please enter content">{{ !empty($announcement->content) ? $announcement->content : "" }}</textarea>
</div>
@if ($authUserRole)
    <div class="mb-3">
        <label for="target" class="form-label">Annnountment For</label>
        <select class="form-control"  name="target">
            <option value="all" {{ (!empty($announcement->target) && $announcement->target == 'all') ? 'selected' : "" }}>Students And Parents</option>
            <option value="students" {{ (!empty($announcement->target) && $announcement->target == 'students') ? 'selected' : "" }}>Students</option>
            <option value="parents" {{ (!empty($announcement->target) && $announcement->target == 'parents') ? 'selected' : "" }}>Parents</option>
        </select>
    </div>
@endif
@if (empty($announcement))
    <div class="d-flex justify-content-end announcement-submit">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
@endif