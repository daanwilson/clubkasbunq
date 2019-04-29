Vue.component('comp-money', {
  props: ['currency','amount'],
  template: "<span>{{ (isNaN(parseFloat(amount))? '' : parseFloat(amount).toLocaleString('nl-NL',{ style: 'currency', currency: currency }) ) }}</span>"
});
Vue.component('comp-date', {
  props: ['datetime'],
  template: "<span>{{ new Intl.DateTimeFormat('nl-NL').format(new Date(datetime)) }}</span>"
});
Vue.component('comp-time', {
  props: ['datetime'],
  template: "<span>{{ new Intl.DateTimeFormat('nl-NL',{hour: 'numeric', minute: 'numeric'}).format(new Date(datetime)) }}</span>"
});
Vue.component('comp-datetime', {
  props: ['datetime'],
  template: "<span>{{ new Intl.DateTimeFormat('nl-NL',{year: 'numeric', month: 'numeric', day: 'numeric',hour: 'numeric', minute: 'numeric'}).format(new Date(datetime)) }}</span>"
});

class App {
    constructor() {
        this.message = null;
        this.forms = [];
        this.tables = [];
    }
    loadVueMessage() {
        this.message = new Vue({
            el: '#app-message',
            data: {
                msg: new Message(),
            },
        })
    }
    loadVueForms() {
        if ($("form.form-vue").length > 0) {
            var app = this;
            $("form.form-vue").each(function (i, form) {
                var formid = app.forms.length + 1;
                $(this).wrap('<span id="form-' + formid + '"></span>');
                app.forms.push(new Vue({
                    el: '#form-' + formid,
                    data: {
                        form: new Form(form),
                    },
                    methods: {
                        onSubmit: function () {
                            app.forms[formid - 1].form.submit();
                        }
                    }
                }));
            });
        }
    }
    loadVueTables() {
        if ($(".table-vue").length > 0) {
            var app = this;
            $(".table-vue").each(function (i, tablewrapper) {
                var tableid = app.tables.length + 1;
                $(tablewrapper).attr("id", 'table-' + tableid);
                app.tables.push(new Vue({
                    el: '#table-' + tableid,
                    data: {
                        table: new Table(tablewrapper),
                    },
                    storage: {
                        data: {
                            form: null
                        },
                        namespace: 'table-storage-' + tableid,
                    },
                    methods: {
                        calcSum: function(column){
                          var table = app.tables[tableid - 1];
                          if(typeof table!=='undefined'){
                            var sum = 0;
                            table.table.rows.forEach(function(row,index){
                                var float = parseFloat(row[column]);
                                if(!isNaN(float)){
                                    sum+=float;
                                }
                                
                            });
                            return sum;
                          }                            
                          return '..';  
                        },
                        onSubmit: function (submit) {
                            var form = new Form(submit.target);
                            form.submit({
                                onSuccess: function () {
                                    app.tables[tableid - 1].table.loadRows();
                                }
                            });
                            //app.tables[tableid-1].table.loadRows();
                            
                        },
                        
                    }
                    
                })
                        );
            });
        }
    }
    file(file) {
        return new AppFile(file);
    }
    
}

class AppFile {
    constructor(file) {
        this.file = file;
    }
    getSize(si) {
        var bytes = 0;
        if (!isNaN(this.file.size) && this.file.size > 0) {
            bytes = this.file.size;
        }
        var thresh = si ? 1000 : 1024;
        if (Math.abs(bytes) < thresh) {
            return bytes + ' B';
        }
        var units = si
                ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
                : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
        var u = -1;
        do {
            bytes /= thresh;
            ++u;
        } while (Math.abs(bytes) >= thresh && u < units.length - 1);
        return bytes.toFixed(1) + ' ' + units[u];
    }
}

class Errors {
    constructor() {
        this.errors = {};
    }
    get(field) {
        if (this.has(field)) {
            return this.errors[field][0];
        }
    }
    has(field) {
        return this.errors.hasOwnProperty(field);
    }
    clear(field) {
        delete this.errors[field];
    }
    record(errors) {
        this.errors = errors;
    }
    any() {
        return Object.keys(this.errors).length > 0;
    }
}
class Message {
    constructor() {
        this.text = null;
        this.type = null;
        this.timer = null;
    }
    set(text, type) {
        this.text = (text ? text : null);
        this.type = (type ? type : 'info');
        if (this.text) {
            var duration = Math.min(this.text.length * 100, 15000);
            if (this.timer) {
                clearTimeout(this.timer);
            }
            this.timer = setTimeout(function () {
                app.message.msg.clear();
            }, duration);
        }
    }
    has() {
        return (this.text !== null);
    }
    getText() {
        return this.text;
    }
    getType() {
        return this.type;
    }
    clear() {
        this.text = null;
        this.type = null;
    }
    
}


class Form {
    constructor(form) {
        this.form = form;
        this.errors = new Errors();
        this.data = {};
        this.load();
    }
    reset() {
        for (let field in this.data) {
            this.data[field] = '';
        }
    }
    load() {
        var elements = $(this.form).find('[v-model]');
        for (var i = 0; i < elements.length; i++) {
            var name = elements[i].getAttribute('name');
            var type = elements[i].getAttribute('type');
            var value = elements[i].value;
            if (type === 'checkbox' && elements[i].checked === false) {
                value = null;
            }
            
            if (name.indexOf('[') >= 0 && name.indexOf(']') >= 0) {
                name = name.substring(0, name.indexOf('['));
                if (typeof this.data[name] === 'undefined') {
                    this.data[name] = [];
                }
                if (value !== null) {
                    this.data[name].push(value);
                }
            } else {
                this.data[name] = value;
            }
            
        }
        this.action = this.form.getAttribute('action');
        
        var method = $(this.form).find('[name="_method"]');
        if (method.length > 0) {
            this.method = method[0].value;
        } else {
            this.method = this.form.getAttribute('method');
        }
        var token = $(this.form).find('[name="_token"]');
        if (token.length > 0) {
            this.token = token[0].value;
        }
    }
    
    submit(options) {

        if (!this.token) {
            alert("Can't submit because of missing csrf-token");
        }
        var form = this;
        var ajax = $.ajax({
            method: this.method,
            url: this.action,
            data: this.data,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': this.token
            },
            // Custom XMLHttpRequest
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    // For handling the progress of the upload
                    var uploading = false;
                    myXhr.upload.addEventListener('progress', function (e) {
                        if (e.lengthComputable) {
                            if (e.total > 1000) {
                                uploading = true;
                                if (e.total === e.loaded) {
                                    uploading = false;
                                }
                                app.message.msg.set('Uploading : ' + (e.loaded / (e.total / 100)).toFixed(0) + '%', 'info');
                            }
                        }
                    }, false);
                    var processingTimer = setInterval(function () {
                        if (myXhr.readyState == 4) {
                            clearInterval(processingTimer);
                        } else {
                            if (uploading == false) {
                                app.message.msg.set('Processing&nbsp;<i class="fas fa-spinner fa-spin"></i> ', 'info');
                            }
                        }
                    }, 100);
                    
                }
                return myXhr;
            }
        })
                .done(function (result) {
                    form.onSuccess(result);
                    // Make sure the callback is a function​
                    if (result.result === true && typeof options === 'object' && typeof options.onSuccess === "function") {
                        // Execute the callback function and pass the parameters to it​
                        options.onSuccess(result);
                    }
                    // Make sure the callback is a function​
                    if (result.result === false && typeof options === 'object' && typeof options.onError === "function") {
                        // Execute the callback function and pass the parameters to it​
                        options.onError(result);
                    }
                    
                })
                .fail(function (result) {
                    form.onFail(result.responseJSON);
                    // Make sure the callback is a function​
                    if (typeof options === 'object' && typeof options.onFail === "function") {
                        // Execute the callback function and pass the parameters to it​
                        options.onFail(result);
                    }
                });
        
        
    }
    onSuccess(result) {
        //alert(result.message);
        app.message.msg.set(result.message, result.status);
        if (result.redirect) {
            window.history.pushState("", "", result.redirect);
            this.action = result.redirect;
        }
        if (result.reload_tables) {
            app.tables.forEach(function(table){
                table.table.loadRows();
            });
        }
        if (result.hide_modal) {
            $(".modal:visible").each(function(i,e){
                $("#"+$(e).attr("id")).modal('hide');
            });
        }
        if (result.data) {
            this.data = result.data;
        }
        return true;
    }
    onFail(result) {
        this.errors.record(result.errors);
        app.message.msg.set(result.message, 'danger');
        return false;
    }
    onFileChange(e) {
        let files = e.target.files || e.dataTransfer.files;
        if (!files.length)
            return;
        
        $(e.target).parent().find('label').text(app.file(files[0]).getSize(false));
        this.createImage($(e.target), files[0]);
    }
    createImage(target, file) {
        let reader = new FileReader();
        let vm = this;
        reader.onload = (e) => {
            //vm.image = e.target.result;
            vm.data[target.attr('name')] = {'name': file.name, 'size': file.size, 'type': file.type, 'content': e.target.result};
        };
        reader.readAsDataURL(file);
    }
    
}
class Table {
    constructor(table) {
        this.table = table;
        this.search = new Search(this);
        this.sorting = new Sorting(this);
        this.errors = new Errors();
        this.selection = new Selection(this);
        this.pagination = new Pagination(this, {'current_page': 1, 'per_page': 0, 'total': 0, 'from': 0, 'to': 0, 'last_page': 0});
        this.rows = [];
        this.load();
        this.loadRows();
    }
    load() {
        var pars = getQueryParams(window.location.href);
        if ('q' in pars) {
            this.search.search = pars.q;
        }
        if ('f' in pars) {
            this.search.setFiltering(pars.f);
        }
        if ('o' in pars) {
            this.sorting.setSorting(pars.o);
        }
        if ('page' in pars) {
            this.pagination.current_page = pars.page;
        }
    }
    loadRows() {
        var src = this.getUrl();
        var table = this;
        if (src) {
            $.get({
                url: src,
                cache: false
            }, function (result) {
                if ('data' in result) {
                    table.rows = result.data;
                    table.pagination = new Pagination(table, result);
                } else {
                    table.rows = result;
                }

                table.selection.load();
                $(table.table).find("input.js-input-number").inputNumber();
                var tableId = $(table.table).attr('id');
                $('#'+tableId).find('tbody').on('click',function(el){
                    if(el.target.tagName=='TD'){
                        var target = $(el.target);
                        var row = target.parents('tr');
                        var edit = row.find('a.js-btn-edit:first');

                        if(edit.length>0){
                            edit[0].click();
                        }
                    }
                });
            })
        }
    }
    edit(path) {
        //console.log(path);
        return path;
    }
    getUrl() {
        var src = $(this.table).data('src');
        
        src = this.search.append(src);
        src = this.sorting.append(src);
        src = this.pagination.append(src);
        //de parameters doorzetten naar huidige url balk.
        var url = window.location.href.split('?')[0];
        if (src.indexOf('?') >= 0) {
            url = url + '?' + decodeURIComponent(src.split('?')[1]);
        }
        window.history.pushState(null, null, url);
        return src;
    }
    saveRow(record) {
        $.ajax({
            method: "POST",
            url: $(this.table).data('src'),
            data: record,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        }).done(function (result) {
            app.message.msg.set(result.message, result.status);
        }).fail(function (result) {
            app.message.msg.set(result.message, result.status);
        });
    }
    addRow(record) {
        alert(record);
        return false;
    }
}

class Search {
    constructor(table) {
        this.table = table;
        this.search = '';
        this.filters = {};
        this.timer = null;
        this.loadFilters($(table.table).find(".multiselect"));
    }
    clear() {
        this.search = '';
    }
    doSearch() {
        if (this.timer !== null) {
            clearTimeout(this.timer);
        }
        var search = this;
        this.timer = setTimeout(function () {
            search.table.loadRows();
        }, 300);
    }
    doFilter(event) {
        this.loadFilters($(event.target).parents('.multiselect,.select'));
        this.table.loadRows();
    }
    loadFilters(inputs) {
        if (inputs.length > 0) {
            var search = this;
            inputs.each(function(index,input){
                
                var filters = [];
                var filter = $(input).find('select').data('field');

                if($(input).hasClass("multiselect")){
                    $(input).find('input:checked').each(function () {
                        filters.push($(this).val());
                    });

                }else if($(input).hasClass("select")){
                    filters = $(input).find('option:selected').val();
                }
                if (filters.length > 0) {
                    search.filters[filter] = filters;
                } else if (filter in search.filters) {
                    delete search.filters[filter];
                }

            });
            
            
        }
    }
    setFiltering(string) {
        var filters = string.split("|");
        var t = this;
        filters.forEach(function (filter) {
            var parts = filter.split(":");
            t.filters[parts[0]] = parts[1].split(',');
        });
    }
    append(url) {
        var pars = [];
        if (this.search != '') {
            pars.push('q=' + encodeURIComponent(this.search));
        }
        if (Object.keys(this.filters).length > 0) {
            var filters = [];
            for (var key in this.filters) {
                if(typeof this.filters[key] == 'object'){
                    filters.push(key + ':' + this.filters[key].join());
                }else if(typeof this.filters[key] == 'string'){
                    filters.push(key + ':' + this.filters[key]);
                }
                
            }
            pars.push('f=' + filters.join('|'));
        }
        if (pars.length > 0) {
            
            url = url + (url.indexOf('?') >= 0 ? '&' : '?') + pars.join("&")
        }
        return url;
    }
    toggleFilters(event) {
        var multiselect = $(event.target).parents(".multiselect");
        if (multiselect.find('input[type=checkbox]:checked').length > 0) {
            multiselect.find('input[type=checkbox]').prop('checked', false);
        } else {
            multiselect.find('input[type=checkbox]').prop("checked", true);
        }
        this.doFilter(event);
        /*if((filter in this.filters) && this.filters[filter].length>0){
         this.filters[filter] = null;
         delete this.filters[filter];
         }else{
         this.filters[filter] = [];
         var x = this;
         $(event.target).parents(".multiselect").find('input[type=checkbox]').each(function() {
         x.filters[filter].push(this.value);
         });
         //console.log(this.filters);
         }
         this.table.loadRows();*/
    }
}
class Sorting {
    constructor(table) {
        this.table = table;
        this.sorting = {};
        this.timer = null;
    }
    clear() {
        this.sorting = {};
    }
    doSort(field) {
        if (!this.sorting[field] || this.sorting[field] == '') {
            this.sorting[field] = 'asc';
        } else if (this.sorting[field] == 'asc') {
            this.sorting[field] = 'desc';
        } else {
            delete this.sorting[field];
        }
        this.table.loadRows();
    }
    setSorting(string) {
        var sorting = string.split(",");
        var t = this;
        sorting.forEach(function (sort) {
            var parts = sort.split("|");
            t.sorting[parts[0]] = parts[1];
        });
    }
    append(url) {
        if (Object.keys(this.sorting).length > 0) {
            var append = [];
            for (var key in this.sorting) {
                append.push(key + '|' + this.sorting[key]);
            }
            url = url + (url.indexOf('?') >= 0 ? '&' : '?') + 'o=' + encodeURIComponent(append.join());
        }
        return url;
    }
}
class Selection {
    
    constructor(table) {
        this.table = table;
        this.selected = [];
        this.checkboxes = [];
    }
    load(){
       this.checkboxes = []; //reset
       var selections = this;
       this.table.rows.forEach(function(row,index){
            selections.checkboxes[index]=row.id;
        });
    }
    toggleSelect(input) {
        //var pagination = (this.table.pagination.last_page>1);   //Is er paginatie aanwezig.
        
        if(input.target.checked){
            this.selected = this.checkboxes;
        }else{
            this.selected = [];
        }
        
        
    }
    doAction(event){
        try { 
            if(this.selected.length<=0) throw "Geen regels geselecteerd!";
            var action = $(event.target).parents('tr').find('select[name=action]').val();
            if(action==='') throw "Geen actie geselecteerd!";
            if($(".modal#"+action).length>0){
                //app.forms[0].data.action=action;
                app.forms[0].form.data.action=action;
                app.forms[0].form.data.ids=this.selected.join(',');
                
                $(".modal#"+action).modal();
            }
        }
        catch(error) {
            app.message.msg.set(error, 'danger');
        }
    }
    
}
class Pagination {
    
    constructor(table, result) {
        this.table = table;
        this.current_page = result.current_page;
        this.per_page = result.per_page;
        this.total = result.total;
        this.from = result.from;
        this.to = result.to;
        this.last_page = result.last_page;
        this.pages = [];
        
        this.load();
    }
    load() {
        var start = Math.max(this.current_page - 5, 1);
        var end = Math.min(start + 5, this.last_page);
        if (start > 1) {
            this.pages.push({
                'page': 0,
                'label': '..',
                'enabled': false,
                'active': false,
            })
        }
        for (let i = start; i <= end; i++) {
            this.pages.push({
                'page': i,
                'label': i,
                'enabled': (i == this.current_page ? false : true),
                'active': (i == this.current_page ? true : false),
            })
            
        }
        if (end < this.last_page) {
            this.pages.push({
                'page': 0,
                'label': '..',
                'enabled': false,
                'active': false,
            })
        }
    }
    prevPage() {
        this.current_page--;
        this.table.loadRows();
    }
    nextPage() {
        this.current_page++;
        this.table.loadRows();
    }
    setPage(page) {
        this.current_page = page;
        this.table.loadRows();
    }
    append(url) {
        if (this.current_page > 1) {
            url = url + (url.indexOf('?') >= 0 ? '&' : '?') + 'page=' + this.current_page;
        }
        return url;
    }
}