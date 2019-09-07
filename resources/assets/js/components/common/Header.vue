<template>
  <!--header start-->
  <header class="header fixed-top clearfix">
    <!--logo start-->
    <div class="brand">

      <router-link :to="{ name: 'report' }" class="logo">
       <img style="width: 120px;height:auto"  :src="'/uploads/logo/'+ company.logo" />
      </router-link>
      <div class="sidebar-toggle-box">
        <div class="fa fa-bars"></div>
      </div>
    </div>
    <!--logo end-->

    <div class="top-nav clearfix">
      <!--search & user info start-->
      <ul class="nav pull-right top-menu">
        <!-- user login dropdown start-->
        <li class="dropdown">
          <a data-toggle="dropdown" class="dropdown-toggle" href="#">
            <img alt="" src="images/avatar1_small.jpg">
            <span class="username">{{ user.name }}</span>
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu extended logout">
            <li><router-link :to="{ name: 'Profile' }"><span class="fa fa-user"></span> Profile</router-link></li>
            <li><router-link :to="{ name: 'SalesTracker' }"><span class="fa fa-map-marker"></span> Track Your Sales</router-link></li>
            <li><a @click.prevent="logout()" href="#"><span class="fa fa-sign-out"></span> Sign out</a></li>
          </ul>
        </li>
        <!-- user login dropdown end -->
      </ul>
      <!--search & user info end-->
    </div>
  </header>
</template>

<script>
export default {
  name: 'dashboardHeader',
  data() {
    return {
      user: JSON.parse(localStorage.getItem('user')),
      company: {
   
                   name:'',
                   logo:'',

       },
    }
  },
  created(){
    this.CompanyInfo();
  },
  methods: {

    logout() {
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      location.reload();
    },
      CompanyInfo(){

             axios.get('/api/v1/companny-info')
             .then(response => {
              
                this.company.name = response.data.data[0].company_name
                this.company.logo = response.data.data[0].company_logo

             })
             .catch(e => {

                 console.log(e)
                  this.$Progress.fail()
             })
       },
  }
}
</script>

<style scoped>
 .navbar-custom-menu>.navbar-nav>li>.dropdown-menu {
    position: absolute;
    left: -70px !important;
    background: #ecf0f4 !important;
}
  .list-group {
    padding-left: 0;
    margin-bottom: 3px;
}
  #profile-box{

    background: rgb(236, 240, 244);
    color: white;
    list-style: none;
    position: relative;
    width: 200px;
}
    
  .list-group-item {
    position: relative;
    display: block;
    padding: 2px 15px;
    margin-bottom: 0px;
    background-color: #00c0ef;
    border: 1px solid #ddd;
    text-align: center;
    height: 26px;
    border-radius: 5px;
    color: white;
  
}

  #profile-box .list-group-item a{

    color: #fff;
    font-size: 13px;
    
  }
  .list-group .list-group-item:hover{

    background: #e1e3e9;
    border:1px solid black;

    
  }
  .list-group .list-group-item:hover a{

       color: #333 !important;

    
  }
  
</style>

