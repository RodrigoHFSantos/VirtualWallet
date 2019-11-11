<template>
  <div id="app">
    <ul class="nav">
      <!-- <li><a href="/">Home</a></li>
      <li><a href="/">About</a></li>
      <li><a href="/">Login</a></li>
      <li><a href="/">Register</a></li> -->
      <li><router-link :to="{ name: 'home' }">Home</router-link></li>
      <li><router-link to="/about">About</router-link></li>
      <li><router-link to="/login">Login</router-link></li>
      <li><router-link to="/register">Register</router-link></li>
    </ul>

    <router-view></router-view>
  </div>
</template>


<script>
export default {
    
}
</script>
