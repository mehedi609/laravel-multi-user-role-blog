@extends('layouts.backend.app')

@section('title', "Post")

@push('css')
  <link
    href="{{ asset('assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}"
    rel="stylesheet"
  >
@endpush

@section('content')
  <div class="container-fluid">
    <!-- Exportable Table -->
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
          <div class="header">
            <h2>
              ALL POSTS <span class="my-auto badge bg-pink">{{$posts->count()}}</span>
            </h2>
          </div>

          <div class="body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>
                      <i class="material-icons">visibility</i>
                    </th>
                    <th>In Approve</th>
                    <th>Status</th>
                    <th>Created At</th>

                    <th>Actions</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>
                      <i class="material-icons">visibility</i>
                    </th>
                    <th>In Approve</th>
                    <th>Status</th>
                    <th>Created At</th>

                    <th>Actions</th>
                  </tr>
                </tfoot>
                <tbody>
                  @foreach ($posts ?? '' as $key => $post)
                    <tr>
                      <td>{{$key + 1}}</td>
                      <td>{{str_limit($post->title, 15)}}</td>
                      <td>{{$post->user->name}}</td>
                      <td>{{$post->view_count}}</td>
                      <td>
                        @if ($post->is_approved)
                            <span class="badge bg-blue">Approve</span>
                        @else
                            <span class="badge bg-pink">Pending</span>
                        @endif
                      </td>
                      <td>
                        @if ($post->status)
                            <span class="badge bg-blue">Published</span>
                        @else
                            <span class="badge bg-pink">Pending</span>
                        @endif
                      </td>
                      <td>
                        {{$post->created_at->toFormattedDateString()}}
                      </td>

                      <td class="text-center">
                        <button
                          class="btn bg-deep-purple waves-effect"
                          onclick="approvePost({{$post->id}})"
                        >
                          <i class="material-icons">done</i>
                        </button>

                        <a
                          href="{{route('admin.post.show', $post->id)}}"
                          class="btn btn-success waves-effect"
                        >
                          <i class="material-icons">visibility</i>
                        </a>

                        <a
                          href="{{route('admin.post.edit', $post->id)}}"
                          class="btn btn-info waves-effect"
                        >
                          <i class="material-icons">edit</i>
                        </a>

                        <button
                          class="btn btn-danger waves-effect"
                          onclick="deletePost({{$post->id}})"
                        >
                          <i class="material-icons">delete</i>
                        </button>

                        <form
                          id="delete-post-form-{{$post->id}}"
                          action="{{route('admin.post.destroy', $post->id)}}"
                          method="POST"
                          class="d-none"
                        >
                          @csrf
                          @method('DELETE')
                        </form>

                        <form
                          action="{{route('admin.post.approve', $post->id)}}"
                          method="POST"
                          class="d-none"
                          id="approval-form"
                        >
                          @csrf
                          @method('PUT')
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- #END# Exportable Table -->

  </div>
@stop

@push('js')
  <!-- Jquery DataTable Plugin Js -->
  <script src="{{ asset('assets/backend/plugins/jquery-datatable/jquery.dataTables.js') }}  "></script>
  <script
    src="{{ asset('assets/backend/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }} "></script>
  <script
    src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js') }} "></script>
  <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/buttons.flash.min.js') }} "></script>
  <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/jszip.min.js') }} "></script>
  <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/pdfmake.min.js') }} "></script>
  <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/vfs_fonts.js') }} "></script>
  <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/buttons.html5.min.js') }} "></script>
  <script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/buttons.print.min.js') }} "></script>

  <!-- Custom Js -->
  <script src="{{ asset('assets/backend/js/pages/tables/jquery-datatable.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
  <script>
    function deletePost(id) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-success waves-effect ml-2',
          cancelButton: 'btn btn-danger waves-effect'
        },
        buttonsStyling: true
      })

      swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
      }).then((result) => {
        if (result.value) {
          event.preventDefault();
          document.getElementById(`delete-post-form-${id}`).submit();
        } else if (
          /* Read more about handling dismissals below */
          result.dismiss === Swal.DismissReason.cancel
        ) {
          swalWithBootstrapButtons.fire(
            'Cancelled',
            'Your file is safe :)',
            'error'
          )
        }
      })
    }

    function approvePost(id) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success waves-effect ml-2',
                cancelButton: 'btn btn-danger waves-effect'
            },
            buttonsStyling: true
        })

        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "Do you want to approve this post?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, approve it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                event.preventDefault();
                document.getElementById(`approval-form`).submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'The post is not approved',
                    'info'
                )
            }
        })
    }
  </script>
@endpush
