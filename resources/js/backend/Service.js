import Swal from "sweetalert2";
import Axios from "axios";
export const MY_COMPANY_END_POINT = "https://mycompany.pe/api01/public/api/";
export const MY_COMPANY_API_KEY = "cris_1255adasd3165adasdas321fa";
export const normalizeLaravelErrorList = object => Object.values(object).flatMap( item => item );
export const isEmpty = value => value.trim().length == 0;
export const generateMessageRequired = attribute => `El campo ${attribute} es requerido.`

export const generateClipboard = nroDocument => {
  navigator.clipboard.writeText(nroDocument)
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })
  Toast.fire({
    icon: 'success',
    html: `Copiado al portapapeles <br> <b>${nroDocument}</b> `
  })
}

export const showErrorUpdateAlert = () => {
  Swal.fire({
    icon: 'warning',
    title: '¡Oh no!',
    html: 'Error encontrador, intente nuevamente <br> <small>Si persiste el error contacte al desarrollador</small> ',
    confirmButtonText: 'Continuar',
  })
}

export const showAlertProccessSuccess = () => {
  return Swal.fire({
    icon: 'success',
    title: '¡Bien!',
    html: 'El proceso se ha concluido con éxito',
    confirmButtonText: 'Continuar',
  })
}

export const showReloadWindowAlert = () => {
  return Swal.fire({
    icon: 'warning',
    title: '¡Oh no!',
    html: `
      Error encontrador, intente nuevamente <br>
      <small>Si persiste el error contacte al desarrollador</small> <br>
      <button class="btn btn-warning" onclick="window.location.reload()"><i class="fas fa-redo-alt"></i> Recargar pagína</button>
    `,
    showConfirmButton: false
  })
}

export const consultDocumentSunat = ({ type, nroDocument}) => {
  const typeDocument = type == '06' ? 'RUC' : 'DNI';
  const digits = type == '06' ? 11 : 8;
  if(!nroDocument) throw {error: `Complate el campo "Nro de ${typeDocument}".`};

  if(nroDocument.trim().length != digits) throw {error: `El campo "Nro de ${typeDocument}" debe tener ${digits} dígitos.`}

  const typeConsult = typeDocument == 'DNI' ? 'persona' : 'empresa';

  const consult = type => nroDocument => Axios.get(`https://facturalahoy.com/api/${type}/${nroDocument}/facturalaya_cris_JPckC4FPGYHtMR5`)
  return consult(typeConsult)(nroDocument);
}


export const listAll = nameTable => Axios.get(`list-all?table=${nameTable}`);

export const listAllActives = nameTable => Axios.get(`list-all-actives?table=${nameTable}`);

export const can = (...listPermission) => listPermission.map(permission => Boolean(permission)).includes(true);

export const setFocus = (nameId, delaySeconds = 0) => {
  if(!delaySeconds) {
    document.getElementById(nameId).focus()
  } else {
    setTimeout(() => {
      document.getElementById(nameId).focus()
    }, delaySeconds * 1000)
  }
}
