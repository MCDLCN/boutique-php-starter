<?php
$categories = ["Clothes", "Shoes", "Accessories", "Sport"];
if (in_array("Shoes", $categories)) {
	echo "Found" ;
}else{echo "Not found";};
echo '<br>';
if (in_array("Electronics", $categories)) {
	echo"Found";
}else{echo "Not found" ;};
echo '<br>';
echo array_search("Sport", $categories);