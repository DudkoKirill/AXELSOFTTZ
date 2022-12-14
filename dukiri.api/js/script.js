BX.ready(function(){
    BX.bindDelegate(
        document.body, 'change', {className: 'iblock' },
        function(e){
            console.log(this);
            let sectsId=this.getAttribute("data-id");
            BX.ajax.runAction('dukiri:api.api.reviews.getsections', {
                data: {
                    iblockID:this.value
                }
            }).then(function (response) {
                let sects=response.data;
                let sectsSelect=document.getElementById(sectsId);
                let options='';
                JSON.parse(sects.result).forEach(element => {
                    options=options+'<option value="'+element.ID+'">['+element.ID+']'+element.NAME+'</option>'
                })
                sectsSelect.innerHTML=options;
            }, function (response) {
                //сюда будут приходить все ответы, у которых status !== 'success'
                console.log(345);
                console.log(response);
            });
        }
    );
});
