@extends('layouts.backend.app')

@section('title', "AdminDashboard")

@push('css')

@endpush

@section('content')
  <div class="container-fluid">
    <!-- Tabs With Icon Title -->
    <div class="row clearfix">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
          <div class="header">
            <h2>
              PROFILE SETTINGS
            </h2>
          </div>

          <div class="body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active">
                <a href="#home_with_icon_title" data-toggle="tab">
                  <i class="material-icons">face</i> {{strtoupper('update profile')}}
                </a>
              </li>
              <li role="presentation">
                <a href="#profile_with_icon_title" data-toggle="tab">
                  <i class="material-icons">change_history</i> {{strtoupper('change password')}}
                </a>
              </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane fade in active" id="home_with_icon_title">
                <!-- Horizontal Layout -->
                <div class="row clearfix">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                      <div class="body">
                        <form
                          class="form-horizontal"
                          method="POST"
                          action="{{route('admin.profile.update')}}"
                          enctype="multipart/form-data"
                        >
                          @csrf
                          @method('PUT')

                          <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                              <label for="user_name">Name</label>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                              <div class="form-group">
                                <div class="form-line">
                                  <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="form-control"
                                    placeholder="Enter your name"
                                    value="{{Auth::user()->name}}"
                                  >
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                              <label for="">Email</label>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                              <div class="form-group">
                                <div class="form-line">
                                  <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="Enter your email address"
                                    value="{{Auth::user()->email}}"
                                  >
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                              <label for="password_2">Profile Image</label>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                              <div class="form-group">
                                <input
                                  type="file"
                                  id="image"
                                  name="image"
                                  class="form-control"
                                >
                              </div>
                            </div>
                          </div>

                          <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                              <label for="about">About Me</label>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                              <div class="form-group">
                                <div class="form-line">
                                  <textarea
                                    class="form-control"
                                    id="about"
                                    name="about"
                                    rows="5"
                                    cols="10"
                                  >
                                    {{Auth::user()->about}}
                                  </textarea>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="row clearfix">
                            <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                              <button
                                type="submit"
                                class="btn btn-primary m-t-15 waves-effect"
                              >
                                UPDATE
                              </button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- #END# Horizontal Layout -->
              </div>
              <div role="tabpanel" class="tab-pane fade" id="profile_with_icon_title">
                <b>Profile Content</b>
                <p>
                  Lorem ipsum dolor sit amet, ut duo atqui exerci dicunt, ius impedit mediocritatem an. Pri ut tation electram moderatius.
                  Per te suavitate democritum. Duis nemore probatus ne quo, ad liber essent aliquid
                  pro. Et eos nusquam accumsan, vide mentitum fabellas ne est, eu munere gubergren
                  sadipscing mel.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- #END# Tabs With Icon Title -->
  </div>
@stop

@push('js')

@endpush
