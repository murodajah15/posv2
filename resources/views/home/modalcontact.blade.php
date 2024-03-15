<?php
$session = session();
// var_dump($vdata);
?>
<!-- Modal -->
{{-- <div class="modal-dialog" style="max-width: 40%;"> --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header bg-info">
            <h5 class="modal-title" id="staticBackdropLabel">
                {{ $vdata['title'] }}
                {{-- @if (isset($vdata))
                    {{ $vdata['title'] }}
                @endif --}}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <!-- Way 1: Display All Error Messages -->
        {{-- //submitnya gak pakai ajax --}}
        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
        <form method="post" class="contact">
            @csrf
            <div class="modal-body">
                <div class="col-md-12">
                    <!-- Widget: user widget style 1 -->
                    <div class="card card-widget widget-user">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-info">
                            <h3 class="widget-user-username">
                                <h2><br>{{ env('APP_CONTACT_NAME') }}</h2>
                            </h3>
                            {{-- <h5 class="widget-user-desc">Founder & CEO</h5> --}}
                        </div>
                        {{-- <div class="widget-user-image">
                            <img class="img-circle elevation-2" src="../dist/img/user1-128x128.jpg" alt="User Avatar">
                        </div> --}}
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header"><i class='fas fa-phone'></i></h5>
                                        <span class="description-text">
                                            <h6>{{ env('APP_CONTACT_HP') }}</h6>
                                        </span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-8 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header"><i class='fas fa-envelope'></i></h5>
                                        <span class="description-text">
                                            {{ env('APP_CONTACT_EMAIL') }}
                                        </span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                    <!-- /.widget-user -->
                </div>

                {{-- <p class="text-center mt-0">
                    <span>
                        <h2>{{ env('APP_CONTACT_NAME') }}</h2> <a class="ml-25"></a>
                        <br />
                        <h6><i class='fas fa-phone'></i> {{ env('APP_CONTACT_HP') }}</h6>
                        <br />
                        <h6><i class='fas fa-envelope'></i> {{ env('APP_CONTACT_EMAIL') }}</h6>
                    </span>
                </p> --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>
