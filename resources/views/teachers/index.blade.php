@extends('layouts.app')
@section('title', 'Teachers')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Teachers</h2>
        <a href="" class="btn btn-primary add-btn">
            <i class="bi bi-plus-circle"></i> Create Teacher
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="teacher-table">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>name</th>
                    <th>email</th>
                    <th>phone</th>
                    <th>subject</th>
                    <th>bio</th>
                    <th>action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    
    {{-- Teacher mode start --}}
    <div class="modal fade" id="teacherAddModal" tabindex="-1" aria-labelledby="teacherAddModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-5" id="teacherAddModalLabel">Teacher Add</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="addForm"  method="POST">
                    @csrf
                    @include('teachers.form')
                </form>
              </div>
          </div>
      </div>
    </div>
    {{-- Teacher model end --}}

    {{-- Teacher edit model start --}}
    <div class="modal fade" id="teacherEditModal" tabindex="-1" aria-labelledby="teacherEditModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-5" id="teacherEditModalLabel">Teacher Add</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="editForm"  method="POST">
                    @csrf
                    <div class="formHtml">

                    </div>
                </form>
              </div>
          </div>
      </div>
    </div>
    {{-- Teacher model end --}}
</div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            //datatable script
            var table = $('#teacher-table').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    "url": "{{ route('admin.teacher') }}",
                    "type": "GET"
                },  
                columns: [
                    { data: 'DT_RowIndex', name: '', orderable: true, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
                    { data: 'subject', name: 'subject' },
                    { data: 'bio', name: 'bio' },
                    { data: 'action', name: 'action' },
                ]
            });

            // open annountment model
            $(document).on('click','.add-btn', function(e) {
                e.preventDefault();
                $("#addForm")[0].reset();
                $("#addForm").validate().resetForm();
                $("#teacherAddModal").modal('show');
            });

            // Jquey validation
            // $('#addForm').validate({
            //     rules: {
            //         name: {
            //             required: true,
            //             maxlength: 255  
            //         },
            //         email: {
            //             required: true,
            //             email: true,
            //             maxlength: 255
            //         },
            //         password: {
            //             required: true,
            //             minlength: 6
            //         },
            //         confirm_password: {
            //             required: true,
            //             equalTo: "#password"
            //         },
            //         phone: {
            //             maxlength: 255
            //         },
            //         subject: {
            //             maxlength: 255
            //         }
            //     },
            //     highlight: function (element) {
            //         $(element).parent().addClass('error')
            //     },
            //     unhighlight: function (element) {
            //         $(element).parent().removeClass('error')
            //     }
            // });

            // Submit form
            $(document).on('submit','#addForm',function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('teacher.store') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: $(this).serialize(),
                    success: function(response) {
                        if(response.status) {
                            $("#teacherAddModal").modal('hide');
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
            $(document).on('click','.edit-btn',function(e) {
                e.preventDefault();
                var self = $(this);
                var id = self.data('id');
                
                $.ajax({
                    url: "{{ route('teacher.edit') }}",
                    type: "GET",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if(response.status) {
                            $('#editForm').find('.formHtml').html(response.data.html);
                            $('#teacherEditModal').modal('show');
                        } else {
                            alert('something went wrong');
                        }
                    }
                });
            });

            // Delete teacher
            $(document).on('click','.delete-btn',function(e) {
                e.preventDefault();
                var self = $(this);
                var id = self.data('id');
                
                $.ajax({
                    url: "{{ route('teacher.delete') }}",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if(response.status) {
                            table.draw();
                            successToastMsg(response.message);
                        } else {
                            toastFailedMsg(response.message)
                        }
                    }
                });
            });

            // Submit form
            $(document).on('submit','#editForm',function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('teacher.update') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: $(this).serialize(),
                    success: function(response) {
                        if(response.status) {
                            $("#teacherEditModal").modal('hide');
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
        });
    </script>
@endsection
