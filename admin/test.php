<?php 

$options = [
    'cost' => 10,
];
$res = password_hash("asdf", PASSWORD_BCRYPT, $options);
echo $res;
if(password_verify("asdf", $res)){
	echo "success";
}
?>