<template>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center">
        <img src="../assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">Forms Automation</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn" @click="changeSidebarClass"></i>
    </div><!-- End Logo -->

    <!-- <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div> --><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <!-- <span class="badge bg-primary badge-number">4</span> -->
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have 0 new notifications
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-chat-left-text"></i>
            <!-- <span class="badge bg-success badge-number">3</span> -->
          </a><!-- End Messages Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
            <li class="dropdown-header">
              You have 0 new messages
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="dropdown-footer">
              <a href="#">Show all messages</a>
            </li>

          </ul><!-- End Messages Dropdown Items -->

        </li><!-- End Messages Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <!-- <img src="../assets/img/profile-img.jpg" alt="Profile" class="rounded-circle"> -->
            <i class="bi bi-person-circle" style="font-size: 30px;"></i>
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ fullname }}</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{ fullname }}</h6>
              <span>Top User</span>
            </li>
            <!-- <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li> -->
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="#" @click="logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->
</template>

<script>
export default {
  name: "CommonHeader",
  props: ['userId', 'name'],
  data() {
    return {
      accountType: 'user',
      hideBackBtn: false,
      currentPage: "",
      previousPath: "/",
      syncOption: "new",
      synchedProducts: 0,
      fullname: "No Name",
    };
  },
  methods: {
    changeSidebarClass() {
      //this.$refs.sidebar.classList.toggle('toggle-sidebar');
      //document.getElementById('sidebar').classList.toggle('sidebar')
      document.getElementsByTagName('body')[0].classList.toggle('toggle-sidebar')
    },
    goPrevious() {
      if (this.previousPath) {
        this.$router.push(this.previousPath);
      } else {
        this.$router.push("/");
      }
    },
    logout() {
      localStorage.removeItem("user_token");
      this.$router.push("/");
    }
  },
  created() { },
  mounted() {
    let currentRoute = this.$router.currentRoute;
    // console.log('Current Route: ')
    // console.log(currentRoute)
    this.currentPage = currentRoute.name;
    let str = currentRoute.path;
    let prev = str.substring(0, str.lastIndexOf("/"));
    this.previousPath = prev;
    this.hideBackBtn = false;
    // console.log('User Data Exists:')
    // console.log(this.previousPath)
    // if (this.$local.userData) {
    //   // console.log(this.$local.userData);
    //   this.fullname = this.$local.userData.fullName;
    //   if (this.$local.userData.accountType === "admin" && prev === "/cashier") {
    //     this.previousPath = "/admin/dashboard-page";
    //   } else if (
    //     this.$local.userData.accountType === "cashier" &&
    //     prev === "/cashier"
    //   ) {
    //     this.hideBackBtn = true;
    //   }
    // } else {
    //   this.$router.push("/");
    // }

    

    console.log(this.previousPath);
    this.accountType = global.vm.$local.userData.accountType;
  },
};
</script>

<style scoped>
.back-btn {
  color: white;
  transition: transform 0.2s;
  transform: scale(1);
}

.back-btn:hover {
  transition: transform 0.2s;
  transform: scale(1.2);
}
</style>
