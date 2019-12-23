<template>
    <p>Administrator Statistics</p>
</template>

<script>
export default {
    mounted() {
        if(this.$store.state.token == ''){
		    this.$router.push({name: 'login'})
		}
    }
}
</script>