var ImpMessage={keyId:null,startrange:null,anySelected:function(){return $H(this.messagelist).keys().detect(function(a){return $("check"+a).checked})},selectRow:function(c,a){var b=$(c.replace(/check/,"row"));if(a){b.addClassName("selectedRow")}else{b.removeClassName("selectedRow").removeClassName("selectedRow-over")}$(c).checked=a},confirmDialog:function(a,b){RedBox.overlay=true;RedBox.showHtml('<div id="RB_confirm"><p>'+b+'</p><input type="button" class="button" onclick="window.location=\''+a+'\';" value="'+IMP.text.yes+'" /><input type="button" class="button" onclick="RedBox.close();" value="'+IMP.text.no+'" /></div>')},submit:function(a){if(!this.anySelected()){alert(IMP.text.mailbox_submit);return}switch(a){case"delete_messages":if(IMP.conf.pop3&&!confirm(IMP.text.mailbox_delete)){return}break;case"spam_report":if(!confirm(IMP.text.spam_report)){return}break;case"nostpam_report":if(!confirm(IMP.text.notspam_report)){return}break}$("actionID").setValue(a);$("messages").submit()},makeSelection:function(b){var a="";switch(parseInt(b)){case-1:if($("checkAll").checked){a="!"}a+=IMP.conf.IMP_ALL;break;case 1:a=$F("filter1");break;default:a=$F("filter2")}if(a.empty()){return}else{if(a.startsWith("!")){this.selectFlagged(parseInt(a.substring(1)),false)}else{if(a.startsWith("+")){this.selectFlagged(a.substring(0,1),null)}else{this.selectFlagged(parseInt(a),true)}}}switch(parseInt(b)){case 1:$("select1").reset();break;default:$("select2").reset()}},selectRange:function(f){var g=f.element().readAttribute("id"),c=$(g),b=0,a,d;if(!c){return}a=c.checked;if(this.startrange!==null&&f.shiftKey){d=[$(this.startrange).readAttribute("id"),c.readAttribute("id")];$H(this.messagelist).keys().detect(function(e){e="check"+e;if(d.indexOf(e)!=-1){++b}if(b){this.selectRow(e,a);if(b==2){return true}}},this)}else{this.selectRow(g,a)}this.startrange=g},updateFolders:function(c){var b=$("targetMailbox1"),a=$("targetMailbox2");if(a){if((c==1&&$F(b)!="")||(c==2&&$F(a)!="")){b.selectedIndex=a.selectedIndex=(c==1)?b.selectedIndex:a.selectedIndex}}},transfer:function(d,b){var c,a;if(this.anySelected()){a=$("targetMbox");a.setValue((b==1)?$F("targetMailbox1"):$F("targetMailbox2"));if($F(a)=="*new*"){c=prompt(IMP.text.newfolder,"");if(c!=null&&c!=""){$("newMbox").setValue(1);a.setValue(c);this.submit(d)}}else{if($F(a)==""){alert(IMP.text.target_mbox)}else{this.submit(d)}}}else{alert(IMP.text.mailbox_selectone)}},selectFlagged:function(a,b){$H(this.messagelist).keys().each(function(f){var c,d=$("check"+f);if(a=="+"){c=!d.checked}else{if(a&this.messagelist[f]){c=b}else{c=!b}}this.selectRow(d.id,c)},this)},flagMessages:function(b){var a=$("flag1"),c=$("flag2");if((b==1&&$F(a)!="")||(b==2&&$F(c)!="")){if(this.anySelected()){document.messages.flag.value=(b==1)?$F(a):$F(c);this.submit("flag_messages")}else{if(b==1){a.selectedIndex=0}else{c.selectedIndex=0}alert(IMP.text.mailbox_selectone)}}},getMessage:function(e,d){if(!d){return e}var a=$H(this.messagelist).keys(),c=a.indexOf(e),b=c+d;if(c!=-1){if(b>=0&&b<a.length){return a[b]}}return""},changeHandler:function(a){var b=a.element().readAttribute("id");if(b.startsWith("filter")){this.makeSelection(b.substring(6))}else{if(b.startsWith("flag")){this.makeSelection(b.substring(4))}else{if(b.startsWith("targetMailbox")){this.updateFolders(b.substring(13))}}}},clickHandler:function(b){var a=b.element(),c=a.readAttribute("id");if(a.match(".msgactions A.widget")){if(a.hasClassName("permdeleteAction")){if(confirm(IMP.text.mailbox_delete)){this.submit("delete_messages")}}else{if(a.hasClassName("deleteAction")){this.submit("delete_messages")}else{if(a.hasClassName("undeleteAction")){this.submit("undelete_messages")}else{if(a.hasClassName("blacklistAction")){this.submit("blacklist")}else{if(a.hasClassName("whitelistAction")){this.submit("whitelist")}else{if(a.hasClassName("whitelistAction")){this.submit("fwd_digest")}else{if(a.hasClassName("spamAction")){this.submit("spam_report")}else{if(a.hasClassName("notspamAction")){this.submit("notspam_report")}else{if(a.hasClassName("viewAction")){this.submit("view_messages")}}}}}}}}}b.stop();return}if(!c){return}switch(c){case"checkheader":case"checkAll":if(c=="checkheader"){$("checkAll").checked=!$("checkAll").checked}this.makeSelection(-1);return}if(c.startsWith("check")&&a.hasClassName("checkbox")){this.selectRange(b)}else{if(!this.sortlimit&&a.match("TH")&&a.up("TABLE.messageList")){document.location.href=a.down("A").href}}},keyDownHandler:function(i){var b=i.element(),j=i.keyCode,d,h,g,a,c,k,f;if(i.altKey||i.ctrlKey){switch(j){case Event.KEY_UP:d=-1;f=-1;break;case Event.KEY_DOWN:d=1;f=1;break;default:return}if(typeof this.messagelist=="undefined"){return}if(b.id.indexOf("check")==0&&b.tagName=="INPUT"){c=b.id.substring(5);this.keyId=this.getMessage(c,d);g=$("subject"+this.keyId)}else{if(b.id.indexOf("subject")==0&&b.tagName=="A"){c=b.id.substring(7);this.keyId=this.getMessage(c,f);g=$("subject"+this.keyId)}else{this.keyId=((d+f)>0)?$H(this.messagelist).keys().first():$H(this.messagelist).keys().last();if(Event.KEY_UP||Event.KEY_DOWN){g=$("subject"+this.keyId)}}}}else{if(j==32&&b.id.indexOf("subject")==0&&b.tagName=="A"){this.startrange="check"+this.keyId;this.selectRow(this.startrange,!$(this.startrange).checked)}else{if(!i.shiftKey){if(j==Event.KEY_LEFT&&$("prev")){h=$("prev").href}else{if(j==Event.KEY_RIGHT&&$("next")){h=$("next").href}}if(h){document.location.href=h}return}else{return}}}if(g){g.focus();k=$("row"+this.keyId);if(i.altKey){a=g.id.replace(/subject/,"check");this.selectRow(a,!$(a).checked)}else{if(c!=g.id&&k.className.indexOf("-over")==-1){k.className+="-over"}}if(c){k=$("row"+c);if(c!=g.id){k.className=k.className.replace(/-over/,"")}}}i.stop()},submitHandler:function(a){if(a.element().readAttribute("id").startsWith("select")){a.stop()}}};document.observe("change",ImpMessage.changeHandler.bindAsEventListener(ImpMessage));document.observe("click",ImpMessage.clickHandler.bindAsEventListener(ImpMessage));document.observe("keydown",ImpMessage.keyDownHandler.bindAsEventListener(ImpMessage));document.observe("submit",ImpMessage.submitHandler.bindAsEventListener(ImpMessage));