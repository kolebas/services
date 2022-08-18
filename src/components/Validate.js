export default class Validate{
  constructor(handleValidateInput){
    this._handleValidateInput = handleValidateInput
  }
  
  checkForm(inputs) {
    const validateCustomInput = this._handleValidateInput();
    if(inputs.length > 0) {
      if(!validateCustomInput){
        return false
      }
      const filteredInputs = inputs.filter(element => element.required === true);
      const isValid = !filteredInputs.some(item => item.value == "" || item.value == null);
      return isValid
    } else {
      return false
    }  
  }
}