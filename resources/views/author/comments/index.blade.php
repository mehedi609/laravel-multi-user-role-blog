@extends('layouts.backend.app')

@section('title', "All Comments")

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
              ALL FAVOURITE POSTS
              <span class="my-auto badge bg-pink">{{count($comments)}}</span>
            </h2>
          </div>

          <div class="body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                <thead>
                  <tr>
                    <th>Comments Info</th>
                    <th>Post Info</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Comments Info</th>
                    <th>Post Info</th>
                    <th>Actions</th>
                  </tr>
                </tfoot>
                <tbody>
                  @foreach ($comments as $key => $comment)
                    <tr>
                      <td>
                        <div class="media">
                          <div class="media-left">
                            <a href="#">
                              <img
                                class="media-object"
                                src="{{asset("storage/profile/{$comment->user->image}")}}"
                                alt="{{$comment->user->name}}"
                                width="80"
                                height="80"
                              >
                            </a>
                          </div>
                          <div class="media-body">
                            <h5 class="media-heading d-inline-block m-b--5">{{$comment->user->name}}</h5>
                            <small> commented {{$comment->created_at->diffForHumans()}}</small>
                            <p>{{$comment->comment}}</p>
                            <a
                              href="{{route('post.details', $comment->post->slug)}}"
                              target="_blank"
                            >
                              Reply
                            </a>
                          </div>
                        </div>
                      </td>

                      <td>
                        <div class="media">
                          <div class="media-left">
                            <a href="#">
                              <img
                                class="media-object"
                                src="{{asset("storage/post/{$comment->post->image}")}}"
                                alt="{{$comment->post->slug}}"
                                width="80"
                                height="80"
                              >
                            </a>
                          </div>
                          <div class="media-body">
                            <a href="{{route('post.details', $comment->post->slug)}}" target="_blank">
                              <h6 class="media-heading">{{str_limit($comment->post->title, 50)}}</h6>
                            </a>
                            <p>Posted By <em>{{$comment->post->user->name}}</em></p>
                          </div>
                        </div>
                      </td>

                      <td>
                        <button
                          class="btn btn-danger waves-effect"
                          onclick="deleteComment({{$comment->id}})"
                        >
                          <i class="material-icons">delete</i>
                        </button>

                        <form
                          id="delete-comment-form-{{$comment->id}}"
                          action="{{route('author.comments.destroy', $comment->id)}}"
                          method="POST"
                          class="d-none"
                        >
                          @csrf
                          @method('DELETE')
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
      function deleteComment(id) {
          const swalWithBootstrapButtons = Swal.mixin({
              customClass: {
                  confirmButton: 'btn btn-success waves-effect ml-2',
                  cancelButton: 'btn btn-danger waves-effect'
              },
              buttonsStyling: true
          })

          swalWithBootstrapButtons.fire({
              title: 'Are you sure?',
              text: "You want to remove post from your fav list",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Yes, remove it!',
              cancelButtonText: 'No, cancel!',
              reverseButtons: true
          }).then((result) => {
              if (result.value) {
                  event.preventDefault();
                  $(`#delete-comment-form-${id}`).submit();
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
  </script>
@endpush
