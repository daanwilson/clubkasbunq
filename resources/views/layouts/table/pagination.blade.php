<span class="pagination-wrapper" >
    <ul class="pagination pagination-sm" v-if="table.pagination.pages.length>1"> 
    <li v-bind:class="{ 'disabled' : table.pagination.current_page==1 }">
        <a href="javascript:void(0);" aria-label="Previous" v-on:click="table.pagination.prevPage()"><span aria-hidden="true">&laquo;</span></a>
    </li> 
    
    <li v-for='page in table.pagination.pages' v-bind:class="{ 'active' : page.active }" >        
        <a href="javascript:void(0);" v-if="page.enabled" v-on:click="table.pagination.setPage(page.page)">@{{ page.label }}</a>
        <span v-else-if="page.active">@{{ page.label }}<span class="sr-only">(current)</span></span>
        <span v-else>@{{ page.label }}</span>
    </li> 
    <li v-bind:class="{ 'disabled' : table.pagination.current_page==table.pagination.last_page }">
        <a href="javascript:void(0);" aria-label="Next" v-on:click="table.pagination.nextPage()"><span aria-hidden="true">&raquo;</span></a>
    </li> 
    
</ul>
<small v-if="table.pagination.total>0">@{{ table.pagination.from }}-@{{ table.pagination.to }} van de @{{ table.pagination.total }}</small>
</span>