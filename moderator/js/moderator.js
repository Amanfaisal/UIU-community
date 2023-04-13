function DeletePostMods(postid, groupID){
	if(confirm('Are You Sure want to Delete this post?') == true){
		var PostContainer = 'postRow'+postid;
		xmlhttp=new XMLHttpRequest();
		xmlhttp.onreadystatechange=function() {
			if(this.readyState == 4 && this.status == 200) {
				console.log(this.responseText);
				var RetJson = JSON.parse(this.responseText);
				if(RetJson.deleted.isMatch('true')){
					document.getElementById(PostContainer).remove();
				}
			}
		}
		xmlhttp.open("GET","adminAjax.php?data=deletePost&post_id="+postid+"&group_id="+groupID, true);
		xmlhttp.send();		
	}
}
	
	
	
