<?php
//instantiate the list
$ll = new JDoublyLinkedList();

//First node goes after the head.
$firstNode = $ll->createNode('First Node');
$ll->addFirst($firstNode);

//10 more nodes
for($i = 0; $i < 5; $i++) {
$node = $ll->createNode('Node-' . $i);
$ll->addNext($firstNode, $node);
$firstNode = $node;
}

//and this is how we iterate...iterate
$it = new JLinkedListIterator($ll);

foreach($it as $k => $v) {
echo '<pre>', $k, ': ', print_r($v, true), '</pre>';
}

unset($ll, $it);

echo 'Peak: ' . number_format(memory_get_peak_usage(), 0, '.', ',') .  " bytes<br>";

echo 'End: ' . number_format(memory_get_usage(), 0, '.', ',') . " bytes<br>";

/*
The output would be something like this:

[sourcecode language="HTML"]
Initial: 126,360 bytes

4d7f677322b38: JNode Object
(
[_value:private] => First Node
[_previous:private] => 4d7f677322b06
[_next:private] => 4d7f677322b69
[_uid:private] => 4d7f677322b38
)

4d7f677322b69: JNode Object
(
[_value:private] => Node-0
[_previous:private] => 4d7f677322b38
[_next:private] => 4d7f677322b95
[_uid:private] => 4d7f677322b69
)

4d7f677322b95: JNode Object
(
[_value:private] => Node-1
[_previous:private] => 4d7f677322b69
[_next:private] => 4d7f677322bbf
[_uid:private] => 4d7f677322b95
)

4d7f677322bbf: JNode Object
(
[_value:private] => Node-2
[_previous:private] => 4d7f677322b95
[_next:private] => 4d7f677322be8
[_uid:private] => 4d7f677322bbf
)

4d7f677322be8: JNode Object
(
[_value:private] => Node-3
[_previous:private] => 4d7f677322bbf
[_next:private] => 4d7f677322c12
[_uid:private] => 4d7f677322be8
)

4d7f677322c12: JNode Object
(
[_value:private] => Node-4
[_previous:private] => 4d7f677322be8
[_next:private] => 4d7f677322b1b
[_uid:private] => 4d7f677322c12
)

Peak: 175,952 bytes
End: 135,080 bytes
[/sourcecode]
Notice that the cleanup was not bad. If I had to create 1000 nodes then this is what the memory utilization looks like:

Initial: 126,376 bytes
Peak: 803,464 bytes
End: 192,440 bytes

That's pretty decent in terms of GC but there is still room for improvement.
*/
