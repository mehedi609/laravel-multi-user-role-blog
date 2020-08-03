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
              ALL FAVOURITE POSTS <span class="my-auto badge bg-pink">{{$posts->count()}}</span>
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
                    <th><i class="material-icons">favorite</i></th>
                    <th><i class="material-icons">comment</i></th>
                    <th><i class="material-icons">visibility</i></th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th><i class="material-icons">favorite</i></th>
                    <th><i class="material-icons">comment</i></th>
                    <th><i class="material-icons">visibility</i></th>
                    <th>Actions</th>
                  </tr>
                </tfoot>
                <tbody>
                  @foreach ($posts ?? '' as $key => $post)
                    <tr>
                      <td>{{$key + 1}}</td>
                      <td>{{str_limit($post->title, 35)}}</td>
                      <td>{{$post->user->name}}</td>
                      <td>{{$post->favourite_to_users->count()}}</td>
                      <td>0</td>
                      <td>{{$post->view_count}}</td>
                      <td class="text-center">
                        <a
                          href="{{route('author.post.show', $post->id)}}"
                          class="btn btn-success waves-effect"
                        >
                          <i class="material-icons">visibility</i>
                        </a>

                        <button
                          class="btn btn-danger waves-effect"
                          onclick="removePostFromFavoriteList({{$post->id}})"
                        >
                          <i class="material-icons">delete</i>
                        </button>

                        <form
                          id="remove-favorite-post-form-{{$post->id}}"
                          action="{{route('author.remove.favourite.posts', $post->id)}}"
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
    function removePostFromFavoriteList(id) {
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
          $(`#remove-favorite-post-form-${id}`).submit();
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
