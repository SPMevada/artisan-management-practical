@extends('layouts.app')
@section('title', 'Students')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Students</h2>
        @if ($userRole)
            <a href="" class="btn btn-primary add-btn">
                <i class="bi bi-plus-circle"></i> Create Student
            </a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="student-table">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Class</th>
                    <th>Roll Number</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    @if ($userRole)
                        <th>action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    
    {{-- student mode start --}}
    <div class="modal fade" id="studentAddModal" tabindex="-1" aria-labelledby="studentAddModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-5" id="studentAddModalLabel">Student Add</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="addForm"  method="POST">
                    @csrf
                    @include('students.form')
                </form>
              </div>
          </div>
      </div>
    </div>
    {{-- student model end --}}

    {{-- student edit model start --}}
    <div class="modal fade" id="studentEditModal" tabindex="-1" aria-labelledby="studentEditModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h1 class="modal-title fs-5" id="studentEditModalLabel">Teacher Add</h1>
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
    {{-- student model end --}}
</div>
@endsection
@section('script')
    <script>
        const userRole = @json($userRole);
    </script>
    <script>
        $(document).ready(function() {
            const columns = [
                    { data: 'DT_RowIndex', name: '', orderable: true, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
                    { data: 'address', name: 'address' },
                    { data: 'class', name: 'class' },
                    { data: 'roll_number', name: 'roll_number' },
                    { data: 'user_id', name: 'user_id' },
                    { data: 'created_at', name: 'created_at' },
                ];

                if (userRole) {
                    columns.push({ data: 'action', name: 'action', orderable: false, searchable: false });
                }
            //datatable script
            var table = $('#student-table').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    "url": "{{ route('student.index') }}",
                    "type": "GET"
                },  
                columns: columns
            });

            // open annountment model
            $(document).on('click','.add-btn', function(e) {
                e.preventDefault();
                $("#addForm")[0].reset();
                $("#addForm").validate().resetForm();
                $("#studentAddModal").modal('show');
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
                    url: "{{ route('student.store') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: $(this).serialize(),
                    success: function(response) {
                        if(response.status) {
                            $("#studentAddModal").modal('hide');
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
                    url: "{{ route('student.edit') }}",
                    type: "GET",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if(response.status) {
                            $('#editForm').find('.formHtml').html(response.data.html);
                            $('#studentEditModal').modal('show');
                        } else {
                            alert('something went wrong');
                        }
                    }
                });
            });

            // Delete student
            $(document).on('click','.delete-btn',function(e) {
                e.preventDefault();
                var self = $(this);
                var id = self.data('id');
                
                $.ajax({
                    url: "{{ route('student.delete') }}",
                    type: "DELETE",
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
                    url: "{{ route('student.update') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: $(this).serialize(),
                    success: function(response) {
                        if(response.status) {
                            table.draw();
                            $("#studentEditModal").modal('hide');
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
