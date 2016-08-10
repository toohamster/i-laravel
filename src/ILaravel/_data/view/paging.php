<?php
//页码范围计算
$init = 1;//起始页码数
//总的页码数
$total_num = (int)ceil($_pager['total'] / $_pager['per_page']);
if (2 > $total_num) return;
$max = $total_num;//结束页码数
$pagelen = $_pager['pagelen'];//要显示的页码个数
$current_page_num = $_pager['current_page'];
$pagelen = ($pagelen % 2) ? $pagelen : $pagelen + 1;//页码个数

$pageoffset = ($pagelen - 1) / 2;//页码个数左右偏移量
//分页数大于页码个数时可以偏移
if ($total_num > $pagelen) {
    //如果当前页小于等于左偏移
    if ($current_page_num <= $pageoffset) {
        $init = 1;
        $max = $pagelen;
    } else {//如果当前页大于左偏移
        //如果当前页码右偏移超出最大分页数
        if ($current_page_num + $pageoffset >= $total_num + 1) {
            $init = $total_num - $pagelen + 1;
        } else {
            //左右偏移都存在时的计算
            $init = $current_page_num - $pageoffset;
            $max = $current_page_num + $pageoffset;
        }
    }
}

?>
<div class="paing">
    <ul>
        <?php if ($current_page_num == 1): ?>
            <li><a href="javascript:;">«</a></li>
        <?php else: ?>
            <li class="paing-active"><a href="<?php echo $this->page_url($_pager['url'], $current_page_num - 1) ?>">«</a>
            </li>
        <?php endif; ?>

        <?php for ($i = $init; $i <= $max; $i++): ?>

            <?php if ($i == $current_page_num): ?>
                <li class="paing-active"><a href="<?php echo $this->page_url($_pager['url'], $i) ?>"><?php echo $i ?></a></li>
            <?php else: ?>
                <li><a href="<?php echo $this->page_url($_pager['url'], $i) ?>"><?php echo $i ?></a></li>
            <?php endif; ?>

        <?php endfor; ?>

        <?php if ($current_page_num == $total_num): ?>
            <li><a href="javascript:;">»</a></li>
        <?php else: ?>
            <li class="paing-active"><a href="<?php echo $this->page_url($_pager['url'], $current_page_num + 1) ?>">»</a>
            </li>
        <?php endif; ?>

    </ul>
</div>