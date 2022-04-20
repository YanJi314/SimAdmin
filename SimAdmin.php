<?php $VERSION="Release 1.5";$PASSWORD="simadmin"; ?>
<?php
	if($_COOKIE["SIMADMINPASSWORD"]==$PASSWORD && $_GET["a"]=="zip"){
		header("Content-Type: application/zip");  
		header("Content-Transfer-Encoding: binary");  
		header('Content-disposition: attachment; filename=SimAdminOutput.zip');
		$zip = new ZipArchive();  
		$zip->open("simadminoutput.zip",ZIPARCHIVE::CREATE);
		createZip(opendir("./".$_GET["dir"]),$zip,"./".$_GET["dir"]);  
		$zip->close();
		echo file_get_contents("./simadminoutput.zip");
		unlink("./simadminoutput.zip");
		exit();
	}
	if($_GET["a"]=="login"&&$_GET["pwd"]==$PASSWORD){
		setcookie("SIMADMINPASSWORD",$_GET["pwd"]);
		header("location:?a=view");
	}elseif($_GET["a"]=="login"&&$_GET["pwd"]!=$PASSWORD){
		echo "<script>alert('密码错误');window.location.href='?'</script>";
	}
?>
<head>
	<title>SimAdmin [<?php echo $VERSION; ?>]</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.js"></script>
	<link href="https://cdn.bootcdn.net/ajax/libs/codemirror/5.59.4/codemirror.css" rel="stylesheet">
	<script src="https://cdn.bootcdn.net/ajax/libs/codemirror/5.59.4/codemirror.js"></script>
	<script src="https://cdn.bootcdn.net/ajax/libs/codemirror/5.59.4/mode/htmlmixed/htmlmixed.js"></script>
	<script src="https://cdn.bootcdn.net/ajax/libs/codemirror/5.59.4/mode/xml/xml.js"></script>
	<script src="https://cdn.bootcdn.net/ajax/libs/codemirror/5.59.4/mode/javascript/javascript.js"></script>
	<script src="https://cdn.bootcdn.net/ajax/libs/codemirror/5.59.4/mode/css/css.js"></script>
	
	<style>
		body{margin:0}
		.header{padding:5px;background:white;border-radius:0 0 5px 5px;box-shadow:0 0 5px 0 black;margin-bottom:10px}
		.errmsg{text-align:center;fill:white;background:#ff7f27;color:white;position:fixed;top:50px;bottom:0;left:0;right:0;width:fit-content;height:fit-content;margin:auto;border-radius:10px;padding:10px}
		.filelist{background:#f1f1f1;border-radius:10px;padding:10px;margin:5px}
		.filelist:hover{background:#f8f8f8;}
		.file ico{margin-right:10px;width:1em;height:1em;display:inline-block;background:url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB0PSIxNjE3OTY2NzMwMjA0IiBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHAtaWQ9IjIwMzciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxkZWZzPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+PC9zdHlsZT48L2RlZnM+PHBhdGggZD0iTTg2Ny42NTU1MzYgMTk1Ljk2MTcyNkw2OTkuODg4MzAzIDI4LjE5NDQ5M0M2ODEuODkxODE4IDEwLjE5ODAwOCA2NTcuNDk2NTgzIDAgNjMyLjEwMTU0MyAwSDIyMy45ODEyNTRDMTcwLjk5MTYwMyAwLjE5OTk2MSAxMjggNDMuMTkxNTY0IDEyOCA5Ni4xODEyMTV2ODMxLjgzNzUzMWMwIDUyLjk4OTY1IDQyLjk5MTYwMyA5NS45ODEyNTQgOTUuOTgxMjU0IDk1Ljk4MTI1NGg1NzUuODg3NTIyYzUyLjk4OTY1IDAgOTUuOTgxMjU0LTQyLjk5MTYwMyA5NS45ODEyNTMtOTUuOTgxMjU0VjI2My45NDg0NDhjMC0yNS4zOTUwNC0xMC4xOTgwMDgtNDkuOTkwMjM2LTI4LjE5NDQ5My02Ny45ODY3MjJ6TTc5Mi4wNzAyOTkgMjU2LjE0OTk3MUg2MzkuOTAwMDJWMTAzLjk3OTY5MWwxNTIuMTcwMjc5IDE1Mi4xNzAyOHpNMjIzLjk4MTI1NCA5MjguMDE4NzQ2Vjk2LjE4MTIxNWgzMTkuOTM3NTEydjIwNy45NTkzODNjMCAyNi41OTQ4MDYgMjEuMzk1ODIxIDQ3Ljk5MDYyNyA0Ny45OTA2MjcgNDcuOTkwNjI2aDIwNy45NTkzODN2NTc1Ljg4NzUyMkgyMjMuOTgxMjU0eiIgZmlsbD0iIiBwLWlkPSIyMDM4Ij48L3BhdGg+PC9zdmc+);background-size:cover;vertical-align: middle;}
		.folder ico{margin-right:10px;width:1em;height:1em;display:inline-block;background:url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB0PSIxNjE3OTY3MTQ2MjgxIiBjbGFzcz0iaWNvbiIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHAtaWQ9IjI4NDYiIGRhdGEtc3BtLWFuY2hvci1pZD0iYTMxM3guNzc4MTA2OS4wLmk0IiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgd2lkdGg9IjIwIiBoZWlnaHQ9IjIwIj48ZGVmcz48c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwvc3R5bGU+PC9kZWZzPjxwYXRoIGQ9Ik0xMjggNDY5LjMzMzMzM3YzNDEuMzMzMzM0aDc2OHYtMzQxLjMzMzMzNEgxMjh6IG0wLTg1LjMzMzMzM2g3NjhWMjk4LjY2NjY2N2gtMzg0LjIxMzMzM2MtMzAuNjc3MzMzLTAuMTcwNjY3LTUwLjYwMjY2Ny0xNC41MDY2NjctNzIuMTA2NjY3LTQxLjA4OC0yLjk0NC0zLjY2OTMzMy0xMi4zNzMzMzMtMTUuOTE0NjY3LTEzLjc4MTMzMy0xNy43NDkzMzRDNDA5Ljg5ODY2NyAyMTkuNzMzMzMzIDQwMC4zODQgMjEzLjMzMzMzMyAzODQgMjEzLjMzMzMzM0gxMjh2MTcwLjY2NjY2N3ogbTc2OC0xNzAuNjY2NjY3YTg1LjMzMzMzMyA4NS4zMzMzMzMgMCAwIDEgODUuMzMzMzMzIDg1LjMzMzMzNHY1MTJhODUuMzMzMzMzIDg1LjMzMzMzMyAwIDAgMS04NS4zMzMzMzMgODUuMzMzMzMzSDEyOGE4NS4zMzMzMzMgODUuMzMzMzMzIDAgMCAxLTg1LjMzMzMzMy04NS4zMzMzMzNWMjEzLjMzMzMzM2E4NS4zMzMzMzMgODUuMzMzMzMzIDAgMCAxIDg1LjMzMzMzMy04NS4zMzMzMzNoMjU2YzQ3Ljc4NjY2NyAwIDc4LjIwOCAyMC4yNjY2NjcgMTA4Ljc1NzMzMyA1OC44MzczMzMgMi4wNDggMi41NiAxMS4xMzYgMTQuMzc4NjY3IDEzLjM1NDY2NyAxNy4xNTIgNi43NDEzMzMgOC4zMiA4LjEwNjY2NyA5LjM0NCA1Ljk3MzMzMyA5LjM0NEg4OTZ6IiBwLWlkPSIyODQ3Ij48L3BhdGg+PC9zdmc+);background-size:cover;vertical-align: middle;}
		a{color:#1e9fff}
		a:hover{text-decoration:none}
		.btn{background:#34a8ff;color:white;padding:0 10px 0 10px;border-radius:3px;margin:5px;display:inline-block;cursor:default}
		.btn:hover{background:#40afff}
		.btn:active{background:#0092ff}
		.CodeMirror{width:100vw;height:calc(100vh - 100px)}
	</style>
	<?php if($_COOKIE["SIMADMINPASSWORD"]==$PASSWORD){ ?><script>
		function viewfilder(ele){
			foldername=ele.getAttribute("fullname")
			window.location.href="?a=view&dir="+foldername
		}
		function viewfile(ele){
			filename=ele.getAttribute("fullname")
			filetype=ele.getAttribute("type")
			window.open("?a=viewfile&name="+filename+"&type="+filetype);
		}
		function savefile(){
			fdata=editor.getValue();
			document.getElementById("savebtn").innerHTML="......"
			$.post("?a=savefile&name=<?php echo $_GET["name"]; ?>",{data:fdata}, function(){
    			document.getElementById("savebtn").innerHTML="成功"
				setTimeout(function(){document.getElementById("savebtn").innerHTML="保存"},1000)
  			});
		}
		function zipfolder(dir){
			window.location.href="?a=zip&dir="+dir
		}
		function delfile(name){
			if(confirm("确认删除["+name+"]？删除后不可恢复！")){
				$.get("?a=delfile&name="+name, function(){
					alert("已删除")
					window.location.reload()
				});
			}
		}
		function cmmfile(name){
			var nname=prompt("输入新文件名（未保存的更改将丢失！）","<?php echo end(explode("/",$_GET["name"])); ?>");
			if(nname.length>0){
				$.get("?a=cmmfile&name="+name+"&nname=<?php echo str_replace(end(explode("/",$_GET["name"])),"",$_GET["name"]); ?>"+nname, function(){
					alert("已重命名")
					window.location.href="?a=viewfile&name=<?php echo str_replace(end(explode("/",$_GET["name"])),"",$_GET["name"]); ?>"+nname+"&type=<?php echo $_GET["type"]; ?>"
				});
			}else{
				alert("您没有输入名称！");
			}
		}
        document.addEventListener('keydown', function (e) {
            if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
                e.preventDefault();
                savefile()
            }
        });
	</script><?php } ?>
</head>
<body>
	<div class="header" onclick="window.open('?a=autoupdate')" title="检测面板更新">
		<font style="font-size:1.5em;"><font style='font-weight:bold;color:#1E9FFF'>Sim</font><font style='font-weight:normal;color:#ff7f27'>Admin</font><font size="0.5em" color="grey" style="padding-left:5px"><?php echo $VERSION; ?></font></font>
	</div>
		<?php
		function createZip($openFile,$zipObj,$sourceAbso,$newRelat = ''){while(($file = readdir($openFile)) != false){if($file=="." || $file=="..")continue;$sourceTemp = $sourceAbso.'/'.$file;$newTemp = $newRelat==''?$file:$newRelat.'/'.$file;  if(is_dir($sourceTemp)){$zipObj->addEmptyDir($newTemp);createZip(opendir($sourceTemp),$zipObj,$sourceTemp,$newTemp);}if(is_file($sourceTemp)) {$zipObj->addFile($sourceTemp,$newTemp); }}}  
		if($_COOKIE["SIMADMINPASSWORD"]){if($_COOKIE["SIMADMINPASSWORD"]==$PASSWORD){
			if(!$_GET["a"]){echo "<script>window.location.href='?pwd=".$PASSWORD."&a=view'</script>";}
			if($_GET["a"]=="view"){
				echo '<center><span class="btn" onclick=zipfolder("'.$_GET["dir"].'") id="savebtn">打包</span>';
				echo '</center>';
				echo "<div style='margin:5px'>当前目录：<a href='?pwd=".$PASSWORD."&a=view'>根目录</a>".$_GET["dir"]."</div>";
				$dir = "./".$_GET["dir"];if(is_dir($dir)){if($dh = opendir($dir)){while (($file = readdir($dh)) !== false) {
					if($file!="." && $file!=".." && $file!=end(explode("/",$_SERVER['PHP_SELF']))){
						if(is_dir("./".$_GET["dir"]."/".$file)){echo "<div class='folder filelist' onclick='viewfilder(this)' fullname='".$_GET["dir"]."/".$file."'><ico></ico><name>".$file."</name></div>";}
						else{echo "<div class='file filelist' onclick='viewfile(this)' oncontextmenu='".'delfile(this.getAttribute("fullname"));return false'."' fullname='".$_GET["dir"]."/".$file."' type='".end(explode(".",$file))."'><ico></ico><name>".$file."</name></div>";}
					}
				}closedir($dh);}}else{echo '<div class="errmsg"><svg t="1617961462162" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2019" width="100" height="100"><path d="M575.986694 832.039919C575.986694 867.356032 547.316113 896.026613 512 896.026613 476.683887 896.026613 448.013306 867.356032 448.013306 832.039919 448.013306 796.723806 476.683887 768.048156 512 768.048156 547.316113 768.048156 575.986694 796.64777 575.986694 832.039919ZM512 255.997465C476.683887 255.997465 448.013306 284.668046 448.013306 319.984159L448.013306 639.998733C448.013306 675.314846 476.683887 703.990496 512 703.990496 547.316113 703.990496 575.986694 675.314846 575.986694 639.998733L575.986694 319.984159C575.986694 284.668046 547.316113 255.997465 512 255.997465ZM1023.979724 896.026613C1023.979724 966.729805 966.709529 1024.005069 896.006336 1024.005069L127.993664 1024.005069C57.290471 1024.005069 0.020276 966.658839 0.020276 896.026613 0.020276 874.868373 5.216059 854.931776 14.39105 837.311737L14.320083 837.24077 398.250384 69.304133 398.392317 69.304133C419.626593 28.133261 462.455047 0.040552 512 0.040552 561.544953 0.040552 604.373407 28.209297 625.67865 69.3751L1008.311272 834.711311C1018.348003 852.838256 1023.979724 873.783595 1023.979724 896.026613ZM959.99303 896.026613C959.99303 885.123073 957.392604 874.868373 952.191753 865.404445L951.613881 864.319667 951.112044 863.239958 568.621355 98.405584C557.499847 77.171308 535.834701 64.027246 512 64.027246 488.023365 64.027246 466.282184 77.318311 455.160675 98.765487L452.12938 104.544211 92.175714 824.527578 92.677551 825.034483 71.154339 866.778159C66.389426 875.948082 64.00697 885.842879 64.00697 896.097579 64.00697 931.413692 92.748517 960.089342 127.993664 960.089342L896.006336 960.089342C931.322449 960.013306 959.99303 931.342726 959.99303 896.026613Z" p-id="2020"></path></svg><br><span>目录不存在</span></div>';}
			}elseif($_GET["a"]=="viewfile"){
				if(file_exists("./".$_GET["name"])){
					if($_GET["type"]=="htm"||$_GET["type"]=="html"||$_GET["type"]=="php"){
						echo '<center><span class="btn" onclick="savefile()" id="savebtn">保存</span>';
						echo '<span class="btn" onclick="window.location.reload()">刷新</span>';
						echo '<span class="btn" onclick=delfile("'.$_GET["name"].'")>删除</span>';
						echo '<span class="btn" onclick=cmmfile("'.$_GET["name"].'")>命名</span></center>';
						echo '<textarea id="code" name="code">'.str_replace("<","&lt;",str_replace("&","&amp;",file_get_contents("./".$_GET["name"]))).'</textarea><script>var editor = CodeMirror.fromTextArea(document.getElementById("code"), {lineNumbers:true,mode:"htmlmixed"});</script>';
					}elseif($_GET["type"]=="js"){
						echo '<center><span class="btn" onclick="savefile()" id="savebtn">保存</span>';
						echo '<span class="btn" onclick="window.location.reload()">刷新</span>';
						echo '<span class="btn" onclick=delfile("'.$_GET["name"].'")>删除</span>';
						echo '<span class="btn" onclick=cmmfile("'.$_GET["name"].'")>命名</span></center>';
						echo '<textarea id="code" name="code">'.str_replace("<","&lt;",str_replace("&","&amp;",file_get_contents("./".$_GET["name"]))).'</textarea><script>var editor = CodeMirror.fromTextArea(document.getElementById("code"), {lineNumbers:true,mode:"javascript"});</script>';
					}elseif($_GET["type"]=="css"){
						echo '<center><span class="btn" onclick="savefile()" id="savebtn">保存</span>';
						echo '<span class="btn" onclick="window.location.reload()">刷新</span>';
						echo '<span class="btn" onclick=delfile("'.$_GET["name"].'")>删除</span>';
						echo '<span class="btn" onclick=cmmfile("'.$_GET["name"].'")>命名</span></center>';
						echo '<textarea id="code" name="code">'.str_replace("<","&lt;",str_replace("&","&amp;",file_get_contents("./".$_GET["name"]))).'</textarea><script>var editor = CodeMirror.fromTextArea(document.getElementById("code"), {lineNumbers:true,mode:"text/css"});</script>';
					}elseif($_GET["type"]=="png"||$_GET["type"]=="jpg"||$_GET["type"]=="jpeg"){
						echo '<center><span class="btn" onclick="window.location.reload()">刷新</span>';
						echo '<span class="btn" onclick=delfile("'.$_GET["name"].'")>删除</span>';
						echo '<span class="btn" onclick=cmmfile("'.$_GET["name"].'")>命名</span><br>';
						echo '<img src="./'.$_GET["name"].'" style="max-width:100vw;max-height:calc(100vh - 100px)"></center>';
					}else{
						echo '<center><span class="btn" onclick="savefile()" id="savebtn">保存</span>';
						echo '<span class="btn" onclick="window.location.reload()">刷新</span>';
						echo '<span class="btn" onclick=delfile("'.$_GET["name"].'")>删除</span>';
						echo '<span class="btn" onclick=cmmfile("'.$_GET["name"].'")>命名</span></center>';
						echo '<textarea id="code" name="code">'.str_replace("<","&lt;",str_replace("&","&amp;",file_get_contents("./".$_GET["name"]))).'</textarea><script>var editor = CodeMirror.fromTextArea(document.getElementById("code"), {lineNumbers:true});</script>';
					}
				}else{echo '<div class="errmsg"><svg t="1617961462162" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2019" width="100" height="100"><path d="M575.986694 832.039919C575.986694 867.356032 547.316113 896.026613 512 896.026613 476.683887 896.026613 448.013306 867.356032 448.013306 832.039919 448.013306 796.723806 476.683887 768.048156 512 768.048156 547.316113 768.048156 575.986694 796.64777 575.986694 832.039919ZM512 255.997465C476.683887 255.997465 448.013306 284.668046 448.013306 319.984159L448.013306 639.998733C448.013306 675.314846 476.683887 703.990496 512 703.990496 547.316113 703.990496 575.986694 675.314846 575.986694 639.998733L575.986694 319.984159C575.986694 284.668046 547.316113 255.997465 512 255.997465ZM1023.979724 896.026613C1023.979724 966.729805 966.709529 1024.005069 896.006336 1024.005069L127.993664 1024.005069C57.290471 1024.005069 0.020276 966.658839 0.020276 896.026613 0.020276 874.868373 5.216059 854.931776 14.39105 837.311737L14.320083 837.24077 398.250384 69.304133 398.392317 69.304133C419.626593 28.133261 462.455047 0.040552 512 0.040552 561.544953 0.040552 604.373407 28.209297 625.67865 69.3751L1008.311272 834.711311C1018.348003 852.838256 1023.979724 873.783595 1023.979724 896.026613ZM959.99303 896.026613C959.99303 885.123073 957.392604 874.868373 952.191753 865.404445L951.613881 864.319667 951.112044 863.239958 568.621355 98.405584C557.499847 77.171308 535.834701 64.027246 512 64.027246 488.023365 64.027246 466.282184 77.318311 455.160675 98.765487L452.12938 104.544211 92.175714 824.527578 92.677551 825.034483 71.154339 866.778159C66.389426 875.948082 64.00697 885.842879 64.00697 896.097579 64.00697 931.413692 92.748517 960.089342 127.993664 960.089342L896.006336 960.089342C931.322449 960.013306 959.99303 931.342726 959.99303 896.026613Z" p-id="2020"></path></svg><br><span>文件不存在</span></div>';}
			}elseif($_GET["a"]=="savefile"){
				$savefile=fopen("./".$_GET["name"],"w");
				fwrite($savefile,$_POST["data"]);
				fclose($savefile);
			}elseif($_GET["a"]=="delfile"){
				unlink("./".$_GET["name"]);
			}elseif($_GET["a"]=="cmmfile"){
				rename(".".$_GET["name"],".".$_GET["nname"]);
			}elseif($_GET["a"]=="autoupdate"){
				$newver=file_get_contents("https://admin.simsoft.top/newVersion");
				if($newver!=$VERSION){
					echo "<div style='padding:10px'><h3 style='margin:0'>发现新版本！</h3>";
					echo "<b>当前版本：</b>".$VERSION."<br>";
					echo "<b>最新版本：</b>".$newver."<br>";
					echo "<b>更新内容：</b>".file_get_contents("https://admin.simsoft.top/newUpdate");
					echo "<br><a href='https://sim-admin.rth.app/newCode.txt'><div class='btn'>点击下载</div></a>";
					echo "<a href='?a=viewfile&name=".end(explode("/",$_SERVER['PHP_SELF']))."'><div class='btn'>打开主文件</div></a>";
					echo "<a onclick=alert('口亨！')><div class='btn'>下次一定</div></a>";
					echo "<br>下载后将其改名、改密码并替换当前的SimAdmin主文件即可。";
					echo "</div>";
				}else{
					echo "<div style='padding:10px'><h3 style='margin:0'>真不戳，已经是最新辣！</h3></div>";
				}
			}
		}
		else{ ?>
			<div class="errmsg">
				<svg t="1617961462162" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2019" width="100" height="100"><path d="M575.986694 832.039919C575.986694 867.356032 547.316113 896.026613 512 896.026613 476.683887 896.026613 448.013306 867.356032 448.013306 832.039919 448.013306 796.723806 476.683887 768.048156 512 768.048156 547.316113 768.048156 575.986694 796.64777 575.986694 832.039919ZM512 255.997465C476.683887 255.997465 448.013306 284.668046 448.013306 319.984159L448.013306 639.998733C448.013306 675.314846 476.683887 703.990496 512 703.990496 547.316113 703.990496 575.986694 675.314846 575.986694 639.998733L575.986694 319.984159C575.986694 284.668046 547.316113 255.997465 512 255.997465ZM1023.979724 896.026613C1023.979724 966.729805 966.709529 1024.005069 896.006336 1024.005069L127.993664 1024.005069C57.290471 1024.005069 0.020276 966.658839 0.020276 896.026613 0.020276 874.868373 5.216059 854.931776 14.39105 837.311737L14.320083 837.24077 398.250384 69.304133 398.392317 69.304133C419.626593 28.133261 462.455047 0.040552 512 0.040552 561.544953 0.040552 604.373407 28.209297 625.67865 69.3751L1008.311272 834.711311C1018.348003 852.838256 1023.979724 873.783595 1023.979724 896.026613ZM959.99303 896.026613C959.99303 885.123073 957.392604 874.868373 952.191753 865.404445L951.613881 864.319667 951.112044 863.239958 568.621355 98.405584C557.499847 77.171308 535.834701 64.027246 512 64.027246 488.023365 64.027246 466.282184 77.318311 455.160675 98.765487L452.12938 104.544211 92.175714 824.527578 92.677551 825.034483 71.154339 866.778159C66.389426 875.948082 64.00697 885.842879 64.00697 896.097579 64.00697 931.413692 92.748517 960.089342 127.993664 960.089342L896.006336 960.089342C931.322449 960.013306 959.99303 931.342726 959.99303 896.026613Z" p-id="2020"></path></svg>
				<br>
				<span>输入的密码有误</span>
			</div>
		<?php }}else{ ?>
			<form action="" method="get" class="loginform" style="text-align:center;background:#0090ff;height:fit-content;width:fit-content;padding:10px;position:fixed;top:50;bottom:0;left:0;right:0;border-radius:10px;margin:auto;color:white">
				<h1 style="margin:0;font-size:1.5em;font-weight:normal">登录SimAdmin</h1>
				<h1 style="margin:0;font-size:0.8em;font-weight:normal">请输入密码以管理您的文件</h1>				
				<input name="a" value="login" style="display:none;"><br>
				<input name="pwd" type="password" autofocus style="text-align:center;background:transparent;width:200px;color:white;border:0;outline:0;padding:5px;border-bottom:1px solid white"><br><br>
				<input style="background:white;border:0;outline:none;padding:5px;border-radius:5px;width:200px" type="submit" value="登录">
			</form>
		<?php } ?>
</body> 
