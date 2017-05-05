<div id="example">
    <p>This is a sample Vue application; start typing to see it update:</p>
    <div style="margin:25px" class="form-group form-inline">
        <label>Hello</label>
        <input class="form-control input-sm" type="text" v-model="name">
        <label> results in <span style="color: red">Hello {{ name }} !</span></label>
    </div>
</div>

<script>
    new Vue({
        el: "#example",
        data: $data
    });
</script>
