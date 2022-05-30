/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function autocompletar(arreglo){
    const inputPaciente = document.querySelector('paciente');
    let indexFocus=-1;
    inputPaciente.addEventListener('input',function(){
        const paciente = this.value;
        if(!paciente) return false;
        //crear lista de sugerencias
        const divList = document.createElement('div');
        divList.setAttribute('id',this.id + '-lista-autocompletar');
        divList.setAttribute('class','lista-autocompletar-items');
        this.parentNode.appendChild(divList);
    })
    inputPaciente.addEventListener('keydown',function(){
        
    })
}