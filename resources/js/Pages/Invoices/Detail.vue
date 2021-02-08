<template>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
            <td colspan="4">
                <table>
                <tr>
                    <td class="title">
                    <img src="/Images/klc-logo.png" style="width:100%; max-width:300px;">
                    </td>
                    <td>
                    Factura #: {{invoice.invoice_number}}<br>Visita: <span class="font-bold">{{invoice.visit.visit_date | formatShortDate}}</span> <br> Factura: <span class="font-bold">{{invoice.invoice_date | formatShortDate}}</span>
                    </td>
                </tr>
                </table>
            </td>
            </tr>
            <tr class="information">
            <td colspan="4">
                <table>
                <tr>
                    <td>
                   <p class="font-bold"> Kids Lab Center Gazcue</p>
                    <p>Calle Jose Joaquin perez No. 203, edif Grupo Medico Bolivar 2, Local 103</p>
                </td>
                </tr>
                <tr>
                    <td>
                        <p>Paciente: <span class="font-bold">{{invoice.visit.child.name}}</span></p>
                        <p>Padre: <span class="font-bold">{{invoice.visit.child.dad_or_mom.name}}</span></p>
                    </td>
                </tr>
                </table>
            </td>
            </tr>

            <tr class="heading">
            <td colspan="3" class="rounded-md rounded-r-none">Método de pago</td>
            <td class="rounded-md rounded-l-none"><span v-if="invoice.payment_method == 'Tarjeta'">No. Autorización</span></td>
            </tr>

            <tr class="details">
            <td colspan="3">{{invoice.payment_method}}</td>
            <td><span v-if="invoice.payment_method == 'Tarjeta'">{{invoice.authorization_number}} </span></td>
            </tr>

            <tr class="heading">
            <td class="rounded-md rounded-r-none">Vacuna</td>
            <td>Precio unitario</td>
            <td>Cantidad</td>
            <td class="rounded-md rounded-l-none">Precio</td>
            </tr>

            <tr class="item" v-for="item in invoice.vaccines" :key="item.id">
            <td><input v-model="item.name" /></td>
            <td>RD{{ item.price | currency}}</td>
            <td>1</td>
            <td>RD{{ item.price | currency}}</td>
            </tr>
            <tr>
                <td colspan="6">
                    
                </td>
            </tr>
           
        </table>
        <div class="flex justify-end">
            <div class="mt-10 w-2/4">
                <div>
                    <div class="flex justify-between px-1 py-1">
                     <span>Subtotal sin ITBIS</span>
                    <span class="font-bold">RD{{total | currency}}</span>
                </div>
                <div class="flex justify-between px-1 py-1">
                    <span>Descuento {{ parseFloat(invoice.discount) > 0 ? '-':''}}{{invoice.discount == ''  ? 0 : invoice.discount}}%</span>
                    <span>RD{{ discounted | currency}}</span>
                </div>
                <div class="flex justify-between  bg-trendy-pink-200 py-2 px-2 rounded-md">
                     <span class="font-bold">Total</span>
                    <span class="font-bold">RD{{totalWithDiscount |currency}}</span>
                </div>
                </div>
               
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props:['invoice'],
    computed:{
        total(){
           return this.calculateTotal();
        },
        discounted(){
            const total = this.calculateTotal();
            return  Math.trunc(total * (this.invoice.discount /100)*100)/100  
        },
        totalWithDiscount(){
            const total = this.calculateTotal();

            const dicount = Math.trunc(total * (this.invoice.discount /100)*100)/100  
            return total - dicount;
        }
    },
    methods:{
        calculateTotal(){
             return this.invoice.vaccines.reduce(function(a, b){
               return (parseInt(a) + parseInt(b.price)) /1;
            }, 0); 
        },
        
    },
}
</script>

<style scoped>
    .invoice-box {
  max-width: 800px;
  margin: auto;
  padding: 30px;
  border: 1px solid #eee;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
  font-size: 16px;
  line-height: 24px;
  font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
  color: #555;
}
.fr{
    float: left;
}
.invoice-box table {
  width: 100%;
  line-height: inherit;
  text-align: left;
}

.invoice-box table td {
  padding: 5px;
  vertical-align: top;
}

.invoice-box table tr td:nth-child(n + 2) {
  text-align: right;
}

.invoice-box table tr.top table td {
  padding-bottom: 20px;
}

.invoice-box table tr.top table td.title {
  font-size: 45px;
  line-height: 45px;
  color: #333;
}

.invoice-box table tr.information table td {
  padding-bottom: 40px;
}

.invoice-box table tr.heading td {
  background: #ded4df;
  border-bottom: 1px solid #ded4df;
  font-weight: bold;
}

.invoice-box table tr.details td {
  padding-bottom: 20px;
}

.invoice-box table tr.item td {
  border-bottom: 1px solid #eee;
}

.invoice-box table tr.item.last td {
  border-bottom: none;
}

.invoice-box table tr.item input {
  padding-left: 5px;
}

.invoice-box table tr.item td:first-child input {
  margin-left: -5px;
  width: 100%;
}

.invoice-box table tr.total td:nth-child(2) {
  border-top: 2px solid #ded4df;
  font-weight: bold;
}

.invoice-box input[type="number"] {
  width: 60px;
}

@media only screen and (max-width: 600px) {
  .invoice-box table tr.top table td {
    width: 100%;
    display: block;
    text-align: center;
  }

  .invoice-box table tr.information table td {
    width: 100%;
    display: block;
    text-align: center;
  }
}

/** RTL **/
.rtl {
  direction: rtl;
  font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial,
    sans-serif;
}

.rtl table {
  text-align: right;
}

.rtl table tr td:nth-child(2) {
  text-align: left;
}


/* top-left border-radius */
.invoice-box tr:first-child th:first-child {
  border-top-left-radius: 6px;
}

/* top-right border-radius */
.heading tr:first-child th:last-child {
  border-top-right-radius: 6px;
}

</style>">
    
