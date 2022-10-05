@inject("periodService", 'CID\Platform\Modules\Academic\Services\Periodic\AcademicPeriodService')

<div class="navbar">

  <a data-toggle="modal" data-target="#aside" class="navbar-item pull-left hidden-lg-up">
    <i class="material-icons">&#xe5d2;</i>
  </a>

  @if($activeUser->isOrganizer())
  <div class="navbar-item pull-left h6" id="pageTitle" style="width: 500px">
    <div class="form-group row m-b-0">
      <label for="inputPassword3" class="col-sm-2 form-control-label p-a-0 text-right">Sekolah : </label>
      <div class="col-sm-10">
        <form action="">
          <select class="form-control form-control-sm c-select" name="active_school" onchange="this.form.submit()">
            @foreach ($activeUser->organizations as $org)
              @foreach ($org->descendantsAndSelf()->with('schools')->get() as $_org)
                @foreach ($_org->schools as $school)
                  <option value="{{ $school->id }}" {{ $school->id == session("active_school") ? 'selected' : '' }}>{{ $school->name }}</option>
                @endforeach
              @endforeach
            @endforeach
          </select>
        </form>
      </div>
    </div>
  </div>
  @else
  <div class="navbar-item pull-left h5 hidden-lg-up" id="pageTitle">
    {{ !empty(school('alias')) ? school('alias') : 'School' }}
  </div>
  @endif

  <ul class="nav navbar-nav pull-right p-l-sm">
    <user-dropdown v-bind:user='profile'></user-dropdown>
    <li class="nav-item dropdown">
      <a class="nav-link p-l b-l" href="" aria-expanded="false" data-toggle="modal" data-target="#rightside" ui-toggle-class="modal-open-aside" ui-target="body">
        <i class="material-icons"></i> <span class="label label-sm up warn {{ $activeUser->unread_notifications <= 0 ? 'hide' : '' }}">
          {{ $activeUser->unread_notifications }}
        </span>
      </a>
    </li>
    {{-- <li class="nav-item dropdown">
      <a class="nav-link p-l b-l" href="{{ route('auth.logout') }}" data-target="#rightside">
        <i class="material-icons">power_settings_new</i>
      </a>
    </li> --}}
  </ul>

  <div class="navbar-item pull-right hidden-lg-down">
    <div class="row m-b-0 b-r">
      <label class="col-sm-12 text-right m-b-0">TAHUN AJARAN : {{ $periodService->getCurrentPeriod() ? $periodService->getCurrentPeriod()->name : '-' }} {{ $periodService->getCurrentPeriod() && $periodService->getCurrentPeriod()->getActiveTerm() ? ' - ' . $periodService->getCurrentPeriod()->getActiveTerm()->name : ''}}</label>
    </div>
  </div>

  <div class="m-x-sm m-t-sm pull-left"><div class="p-x-sm p-y-xs bg-primary" id="clockSrv">{{ carbon()->format('d M Y H:i:s') }}</div></div>

</div>

<!-- right side -->
<div class="modal fade inactive text-black" id="rightside" data-backdrop="false">
  <div class="right w-xl white b-l">
    <div class="row-col">
      <a data-dismiss="modal" class="pull-right text-muted text-lg p-a-sm m-r-sm">&times;</a>
        @if(isMobile())
          <div class="p-a b-b purple-700">
        @else
          <div class="p-a b-b">
        @endif
        <span class="label success pull-right">{{ $activeUser->unread_notifications }}</span>
        <span class="">Latest Notification</span>
      </div>
      <div class="row-row">
        <div class="row-body">

          <div class="box">
            <ul class="list no-border p-b">
              @foreach($activeUser->notifications()->take(5)->get() as $notification)
                <li class="list-item">
                  <div class="list-body">
                    <div class="pull-right text-muted text-xs">
                      <span class="hidden-xs">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <div>
                      <a href="{{ route('app.notification.single', [ $notification->id ]) }}" class="_500">{{ $notification->data['title'] }}</a>
                    </div>
                    <div class="text-ellipsis text-muted text-sm">
                      {{ $notification->data['text'] }}
                    </div>
                  </div>
                </li>
              @endforeach
            </ul>
          </div>

        </div>
      </div>
      <div class="p-a b-t">
        Bottom
      </div>
    </div>
  </div>
</div>
