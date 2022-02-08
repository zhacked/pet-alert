<nav class="main-header navbar navbar-expand  sidebar-blue navbar-light" class="navsticky">

    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link text-white" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
            <button id="logout" class="btnlogout"><i class="nav-icon fa fa-power-off"></i> Logout</button>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
          </form>
        </a>
    </ul>
  </nav>
