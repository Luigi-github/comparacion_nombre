<script>
export default {
    mounted() {
        const token = localStorage.getItem('token');
        if(!!token){
            this.$router.push('searcher');
        }else{
            this.$router.push('login');
        }
    }
}
</script>
