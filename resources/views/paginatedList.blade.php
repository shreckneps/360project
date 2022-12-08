@php
    $pageSize = 15;
    
    $pages = ceil($products->count() / $pageSize);
    $paginated;
    if($pages > 1) {
        $paginated = true;
        $products = $products->forPage($page, $pageSize);
    }
@endphp

@include('list')

<script>
    function getPage(page) {
        if(page < 1 || page > parseInt($("#maxPage").val())) {
            return false;
        }
        var toSend = useQuery + "&page=" + page;
        $("#main-content").load(useRoute, toSend);
        return false;
    }

    function boundsCheck(id) {
        var maxVal = parseInt($("#maxPage").val());
        var curVal = parseInt($("#" + id).val());
        var boundVal = Math.max(1, curVal);
        boundVal = Math.min(boundVal, maxVal);
        console.log(boundVal);
        $("#" + id).val(boundVal);
    }
</script>
