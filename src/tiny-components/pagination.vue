<template>
    <div>
        <div class="pull-right">
          <paginate
            :page-count="pagination.pages"
            :click-handler="method"
            :prev-text="'<'"
            :next-text="'>'"
            :container-class="'pagination'"
            :page-class="'page-item'"
            v-model="page">
          </paginate>
        </div>
    </div>
</template>
<script>
import Paginate from 'vuejs-paginate'
export default {
    props: ['pagination', 'method'],
    data () {
        return {
            page: 1,
        }
    },
    components:{
        'Paginate': Paginate
    }
}
</script>