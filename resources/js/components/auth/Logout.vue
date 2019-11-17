<template>
    
</template>

<script>
export default {
    created: function() {
        this.$store.dispatch('destroyToken')
            .then(response => {
                this.$router.push({ name: "home" })
            })
    }    
}
</script>