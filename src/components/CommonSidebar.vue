<template>
    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <!-- <a class="nav-link " href="index.html"> -->
                <router-link class="nav-link" :to="'/'"
                    @click.native="closeSidebar">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </router-link>
            </li><!-- End Dashboard Nav -->


            
            <li clas="nav-item">
                <router-link :to="'/user/list-weekly'" class="nav-link"
                    @click.native="closeSidebar">
                    <i class="bi bi-table"></i>
                    <span>Weekly Timesheets</span>
                </router-link>
            </li>
            <li clas="nav-item">
                <router-link :to="'/user/list-foreman-reports'" class="nav-link"
                    @click.native="closeSidebar">                    
                    <i class="bi bi-graph-up"></i>
                    <span>Foreman Reports</span>
                </router-link>
            </li>

            <li class="nav-item" style="margin-bottom: 0px;">
                <router-link :to="'/employees'" class="nav-link"
                    @click.native="closeSidebar">
                    <i class="bi bi-people-fill"></i>
                    <span>Employees</span>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link :to="'/users'" class="nav-link"
                    @click.native="closeSidebar">
                    <i class="bi bi-person"></i>
                    <span>Users</span>
                </router-link>
            </li>

            <li class="nav-item" style="">
                <!-- <a class="nav-link " href="index.html"> -->
                <a class="nav-link" href="#" @click="logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </li><!-- End Dashboard Nav -->
        </ul>

    </aside><!-- End Sidebar-->
</template>

<script>
    export default {
        name: "CommonSidebar",
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
            closeSidebar() {
                let body = document.getElementsByTagName("body")[0]
                if (body.clientWidth < 800) {
                    body.classList.remove('toggle-sidebar')
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

            //console.log(this.previousPath);
            console.log(this.$local.userData)
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