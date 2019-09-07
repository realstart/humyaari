<template>
    <div>

    </div>  
</template>


<script>
    export default {
        props:['invoiceview', 'offset'],
        methods: {
            isCurrentPage(page){
                return this.pagination.current_page === page
            },
            changePage(page) {
                if (page > this.pagination.last_page) {
                    page = this.pagination.last_page;
                }
                this.pagination.current_page = page;
                this.$emit('paginate');
            }
        },
        computed: {
            pages() {
                let pages = []

                let from = this.pagination.current_page - Math.floor(this.offset / 2)

                if (from < 1) {
                    from = 1
                }

                let to = from + this.offset -1

                if (to > this.pagination.last_page) {
                    to = this.pagination.last_page
                }

                while (from <= to) {
                    pages.push(from)
                    from++
                }

                return pages
            }
        }
    }
</script>