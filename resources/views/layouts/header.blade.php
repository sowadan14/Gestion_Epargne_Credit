 <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
          <span class="sr-only">Toggle sidebar</span>

          <span class="icon-bar"></span>

          <span class="icon-bar"></span>

          <span class="icon-bar"></span>
        </button>

        <div class="navbar-header pull-left">
          <a href="index.html" class="navbar-brand">
            <small style="font-family:'ga_isar_catregular';font-size:25px;font-style:italic;margin-left: -10px;margin-right: 0px;display:inline-block">
          
            <span>
            @if(auth()->user()->entreprise->LogoEntreprise)
              <img src ="{{ asset('storage/images/'.auth()->user()->entreprise->LogoEntreprise) }}" style="width:30px;height:30px;border-radius:100px;" alt="LogoScte" />
            @endif
                <i></i></span>
              {{ auth()->user()->entreprise->NomReduit}}
            </small>
          </a>
        </div>

        <div class="navbar-buttons navbar-header pull-right" role="navigation">
        <ul class="nav ace-nav"  style="background-color:{{auth()->user()->entreprise->ColorEntete}}">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            <!-- @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif -->
                        @else
                            <li class="light-blue">
                                <!-- <a href="#">
                <img class="nav-user-photo" src="{{ url('assets/images/avatars/user.jpg') }}" alt="Jason's Photo" />
                <span class="user-info">
                  <small>Welcome,</small>
                  {{ Auth::user()->email }}
                </span>
              </a> -->
              <a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link">
              @if(auth()->user()->ImageUser)
                <span><img src ="{{ asset('storage/images/'.auth()->user()->ImageUser) }}" style="width:30px;height:30px;border-radius:100px;" alt="avatar" />
                <i></i></span>
                @endif
                <span class="user-name" style="font-family:'ga_isar_catregular'"> {{ auth()->user()->employe->Nom }}</span></a>
              </li>
            <li class="light-blue">
            <a class="log-out-btn" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();"> 
            <img class="nav-user-photo" src="{{ url('assets/images/Files/LogOff.png') }}" />
            </a>

 <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          {{ csrf_field() }}
  </form>
            <!-- <a class="nav-link" href="login">{{ __('Logout') }}</a> -->
              <!-- <a href="/login"> -->
                <!-- <img class="nav-user-photo" src="{{ url('assets/images/Files/LogOff.png') }}" /> -->
                <!-- <a class="dropdown-item" href="{{ route('login') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a> -->
              <!-- </a> -->
            </li>

                                <!-- <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div> -->
                            </li>
                        @endguest
                    </ul>
          <!-- <ul class="nav ace-nav">
            <li class="light-blue">
              <a href="#">
                <img class="nav-user-photo" src="{{ url('assets/images/avatars/user.jpg') }}" alt="Jason's Photo" />
                <span class="user-info">
                  <small>Welcome,</small>
                  JasonsemegloablamJohsoncoucoucoucoucuc
                </span>
              </a>
            </li>
            <li class="light-blue">
              <a href="/login">
                <img class="nav-user-photo" src="{{ url('assets/images/Files/LogOff.png') }}" />
              </a>
            </li>
          </ul> -->
        </div>