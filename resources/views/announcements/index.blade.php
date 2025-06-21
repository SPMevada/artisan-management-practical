@extends('layouts.app')
@section('title', 'Announcements')
@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Announcements</h2>
            <a href="" class="btn btn-primary add-btn">
                <i class="bi bi-plus-circle"></i> Create Announcement
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="announcements-table">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Created By</th>
                        <th>Annnountment For</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        
        {{-- Annnountment mode start --}}
        <div class="modal fade" id="annountAddModal" tabindex="-1" aria-labelledby="annountAddModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="annountAddModalLabel">Annnountment Add</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addForm" enctype="multipart/form-data" method="POST">
                            @csrf
                            @include('announcements.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- Annnountment mode end --}}

        <div class="modal fade" id="annountVIewModal" tabindex="-1" aria-labelledby="annountVIewModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="annountVIewModalLabel">Annnountment Add</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="viewForm" enctype="multipart/form-data" method="POST">
                            @csrf
                            <div class="announcemtn-html">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- Annnountment mode end --}}
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            //datatable script
            var table = $('#announcements-table').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    "url": "{{ route('user.announcement') }}",
                    "type": "GET"
                },  
                columns: [
                    { data: 'DT_RowIndex', name: '', orderable: true, searchable: false },
                    { data: 'title', name: 'title' },
                    { data: 'content', name: 'content' },
                    { data: 'created_by_id', name: 'created_by_id' },
                    { data: 'target', name: 'target' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action' },
                ]
            });

            // open annountment model
            $(document).on('click','.add-btn', function(e) {
                e.preventDefault();
                $("#addForm")[0].reset();
                $("#addForm").validate().resetForm();
                $("#annountAddModal").modal('show');
            });

            // Jquey validation
            $('#addForm').validate({
                rules: {
                    title: {
                        required: true,
                        maxlength: 255
                    },
                    content: {
                        required: true,
                    },
                },
                highlight: function (element) {
                    $(element).parent().addClass('error')
                },
                unhighlight: function (element) {
                    $(element).parent().removeClass('error')
                }
            });

            // Submit form
            $(document).on('submit','#addForm',function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('announcement.store') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: $(this).serialize(),
                    success: function(response) {
                        if(response.status) {
                            $("#annountAddModal").modal('hide');
                            table.draw();
                            successToastMsg(response.message);
                        } else {
                            toastFailedMsg(response.message)
                        }
                    },
                    error: function(response) {
                        toastFailedMsg(response.responseJSON.message)
                    }
                });
            });

            // View content
            $(document).on('click','.view-btn',function(e) {
                e.preventDefault();
                var self = $(this);
                var id = self.data('id');
                
                $.ajax({
                    url: "{{ route('announcement.view') }}",
                    type: "GET",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if(response.status) {
                            $('#viewForm').find('.announcemtn-html').html(response.data.formHtml);
                            $('#annountVIewModal').modal('show');
                        } else {
                            alert('something went wrong');
                        }
                    }
                });
            });
        });
    </script>
@endsection
