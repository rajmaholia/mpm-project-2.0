let actionTargetsField = $("#actionTargets")[0];
let actionTargets = [];
/** Checks all checkbox in object list table */
on("#checkAllCheckbox","change",(e)=>{
  (e.target.checked)?check(".class-object-item"):uncheck(".class-object-item");
  if(e.target.checked) {
    $(".class-object-item").forEach((elem)=>{
      targetID = elem.parentNode.parentNode.id;
      if(actionTargets.indexOf(targetID) == -1){
        actionTargets.push(targetID);
      }
    });
    actionTargetsField.value = JSON.stringify(actionTargets);
  } 
  else {
    actionTargets=[];
  }
}, false);
/* CheckAll End  **/

/** Prepare for Action **/

on('tbody','change',(e)=>{
  trow = e.target.parentNode.parentNode;
  targetid = trow.nodeName=='TR'?trow.id:null;
  (e.target.checked)?actionTargets.push(targetid):remove(actionTargets,targetid);
  actionTargetsField.value = JSON.stringify(actionTargets);
});

