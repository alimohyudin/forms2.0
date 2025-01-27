<template>
  <div>
    <nav class="navbar navbar-light bg-light navbar-expand-lg">
      <button
        class="navbar-toggler"
        data-toggle="collapse"
        data-target="#subNavbarCollapse"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="subNavbarCollapse">
        <ul class="navbar-nav">
          <li clas="navbar-item">
            <router-link :to="'/user/dashboard'+( userId ? '/'+userId : '')" class="nav-link"
              >&nbsp;Dashboard</router-link
            >
          </li>
          <li clas="navbar-item">
            <router-link :to="'/user/bots'+( userId ? '/'+userId : '')" class="nav-link"
              >&nbsp;Bots</router-link
            >
          </li>
          <li clas="navbar-item">
            <router-link to="/user/pairs" class="nav-link"
              >&nbsp;Pairs</router-link
            >
          </li>
          <li clas="navbar-item">
            <router-link :to="'/user/stats'+( userId ? '/'+userId : '')" class="nav-link"
              >&nbsp;Stats</router-link
            >
          </li>
          
          <!-- <li clas="navbar-item">
            <router-link to="/user/details" class="nav-link"
              >&nbsp;Create Bot</router-link
            >
          </li>
           <li clas="navbar-item">
            <router-link to="/user/admin-forms" class="nav-link"
              >&nbsp;Forms</router-link
            >
          </li>
          <li clas="navbar-item">
            <router-link to="/user/timesheets" class="nav-link"
              >&nbsp;Timesheets</router-link
            >
          </li> -->
          <li clas="navbar-item">
            <router-link to="/" class="nav-link"
              >&nbsp;Logout</router-link
            >
          </li>
        </ul>
      </div>
    </nav>
  </div>
</template>

<script>
export default {
  name: "CommonHeader",
  props: ['userId'],
  data() {
    return {
      accountType: 'user',
      hideBackBtn: false,
      currentPage: "",
      previousPath: "/",
      syncOption: "new",
      synchedProducts: 0,
    };
  },
  methods: {
    goPrevious() {
      if (this.previousPath) {
        this.$router.push(this.previousPath);
      } else {
        this.$router.push("/");
      }
    },
  },
  created() {},
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
    if (this.$local.userData) {
      // console.log(this.$local.userData);
      if (this.$local.userData.accountType === "admin" && prev === "/cashier") {
        this.previousPath = "/admin/dashboard-page";
      } else if (
        this.$local.userData.accountType === "cashier" &&
        prev === "/cashier"
      ) {
        this.hideBackBtn = true;
      }
    } else {
      this.$router.push("/");
    }

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
