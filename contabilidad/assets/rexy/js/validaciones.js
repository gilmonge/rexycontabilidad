/**************************************************
    Sistema de contabilidad
    Desarrollador: Rexy Studios
    Año de creación: 2020
    Última modificación del archivo: 21-04-2020
**************************************************/

document.addEventListener('DOMContentLoaded', function() {
    if ( document.getElementById( "formTercero" )) {
        FormValidation.formValidation(
            document.getElementById('formTercero'),
            {
                fields: {
                    identificacion: {
                        validators: {
                            notEmpty: {
                                message: 'The name is required'
                            },
                        }
                    },
                    nombre: {
                        validators: {
                            notEmpty: { message: 'Este campo es obligatorio.' },
                            stringLength: { max: 70, message: 'El tamaño del nombre debe ser como máximo 70 caracteres.' },
                            regexp: {
                                regexp: /^[a-zA-Z /\s/Ñ/ñ/à/è/ì/ò/ù/À/È/Ì/Ò/Ù/á/é/í/ó/ú/ý/Á/É/Í/Ó/Ú/Ý/â/ê/î/ô/û/Â/Ê/Î/Ô/Û/ã/ñ/õ/Ã/Ñ/Õ/ä/ë/ï/ö/ü/ÿ/Ä/Ë/Ï/Ö/Ü/Ÿ/ç/Ç/]+$/,
                                message: 'Solo puede contener valores alfabéticos'
                            }
                        }
                    },
                    apellido: {
                        validators: {
                            stringLength: { max: 70, message: 'El tamaño del apellido debe ser como máximo 70 caracteres.' },
                            regexp: {
                                regexp: /^[a-zA-Z /\s/Ñ/ñ/à/è/ì/ò/ù/À/È/Ì/Ò/Ù/á/é/í/ó/ú/ý/Á/É/Í/Ó/Ú/Ý/â/ê/î/ô/û/Â/Ê/Î/Ô/Û/ã/ñ/õ/Ã/Ñ/Õ/ä/ë/ï/ö/ü/ÿ/Ä/Ë/Ï/Ö/Ü/Ÿ/ç/Ç/]+$/,
                                message: 'Solo puede contener valores alfabéticos'
                            }
                        }
                    },
                    correo: {
                        validators: {
                            notEmpty: { message: 'El correo es obligatorio.' },
                            regexp: {
                                regexp: /[a-z0-9]+([-._]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*\s*/,
                                message: ' Correo inválido. '
                            }
                        }
                    },
                    telefono: {
                        validators: {
                            stringLength: { min: 8, max: 11, message: 'El tamaño del teléfono debe ser mínimo 8 caracteres y máximo 11.' },
                            notEmpty: { message: 'El teléfono es obligatorio.' },
                            regexp: { regexp: /^[0-9]+$/, message: 'Solo puede contener valores numéricos' }
                        }
                    },
                    direccion: {
                        validators: {
                            regexp: {
                                regexp: /^[a-zA-Z0-9 /\s/Ñ/ñ/à/è/ì/ò/ù/À/È/Ì/Ò/Ù/á/é/í/ó/ú/ý/Á/É/Í/Ó/Ú/Ý/â/ê/î/ô/û/Â/Ê/Î/Ô/Û/ã/ñ/õ/Ã/Ñ/Õ/ä/ë/ï/ö/ü/ÿ/Ä/Ë/Ï/Ö/Ü/Ÿ/ç/Ç/,/./-/?]+$/,
                                message: 'Solo puede contener valores alfabéticos'
                            }
                        }
                    },
                    clasificacion: {
                        validators: {
                            notEmpty: {
                                message: 'Este campo es requerido'
                            },
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap(),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    icon: new FormValidation.plugins.Icon({
                        valid: 'fa fa-check',
                        invalid: 'fa fa-times',
                        validating: 'fa fa-refresh',
                    })
                }
            }
        );
    }

    if ( document.getElementById( "form_cuenta" )) {
        FormValidation.formValidation(
            document.getElementById('form_cuenta'),
            {
                fields: {
                    nombre: {
                        validators: {
                            notEmpty: { message: 'Este campo es obligatorio.' },
                            stringLength: { max: 70, message: 'El tamaño del nombre debe ser como máximo 70 caracteres.' },
                            regexp: {
                                regexp: /^[a-zA-Z /\s/Ñ/ñ/à/è/ì/ò/ù/À/È/Ì/Ò/Ù/á/é/í/ó/ú/ý/Á/É/Í/Ó/Ú/Ý/â/ê/î/ô/û/Â/Ê/Î/Ô/Û/ã/ñ/õ/Ã/Ñ/Õ/ä/ë/ï/ö/ü/ÿ/Ä/Ë/Ï/Ö/Ü/Ÿ/ç/Ç/]+$/,
                                message: 'Solo puede contener valores alfabéticos'
                            }
                        }
                    },
                    id_cuenta_financiero: {
                        validators: {
                            notEmpty: { message: 'Este campo es obligatorio.' },
                        }
                    },
                    id_cuenta_control: {
                        validators: {
                            notEmpty: { message: 'Este campo es obligatorio.' },
                        }
                    },
                    codigo: {
                        validators: {
                            stringLength: { min: 1, message: 'El tamaño del teléfono debe ser mínimo 1 caracter.' },
                            notEmpty: { message: 'Este campo es obligatorio.' },
                            regexp: { regexp: /^[0-9]+$/, message: 'Solo puede contener valores numéricos' }
                        }
                    },
                    id_moneda: {
                        validators: {
                            notEmpty: { message: 'Este campo es obligatorio.' },
                        }
                    },
                    monto_inicial: {
                        validators: {
                            regexp: {
                                regexp: /^[0-9]+(\.[0-9]{1,2})+$/,
                                message: 'Solo puede contener valores numéricos'
                            }
                        }
                    },
                    naturaleza: {
                        validators: {
                            notEmpty: { message: 'Este campo es obligatorio.' },
                        }
                    },
                    comentario: {
                        validators: {
                            regexp: {
                                regexp: /^[a-zA-Z0-9/\s/Ñ/ñ/à/è/ì/ò/ù/À/È/Ì/Ò/Ù/á/é/í/ó/ú/ý/Á/É/Í/Ó/Ú/Ý/â/ê/î/ô/û/Â/Ê/Î/Ô/Û/ã/ñ/õ/Ã/Ñ/Õ/ä/ë/ï/ö/ü/ÿ/Ä/Ë/Ï/Ö/Ü/Ÿ/ç/Ç/,/./%/&/-/$]+$/,
                                message: 'Solo puede contener valores alfanuméricos'
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap(),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    icon: new FormValidation.plugins.Icon({
                        valid: 'fa fa-check',
                        invalid: 'fa fa-times',
                        validating: 'fa fa-refresh',
                    })
                }
            }
        );
    }

    
    if ( document.getElementById( "form_item" )) {
        FormValidation.formValidation(
            document.getElementById('form_item'),
            {
                fields: {
                    nombre_item: {
                        validators: {
                            notEmpty: { message: 'Este campo es obligatorio.' },
                            stringLength: { max: 70, message: 'El tamaño del nombre debe ser como máximo 70 caracteres.' },
                            regexp: {
                                regexp: /^[a-zA-Z /\s/Ñ/ñ/à/è/ì/ò/ù/À/È/Ì/Ò/Ù/á/é/í/ó/ú/ý/Á/É/Í/Ó/Ú/Ý/â/ê/î/ô/û/Â/Ê/Î/Ô/Û/ã/ñ/õ/Ã/Ñ/Õ/ä/ë/ï/ö/ü/ÿ/Ä/Ë/Ï/Ö/Ü/Ÿ/ç/Ç/]+$/,
                                message: 'Solo puede contener valores alfabéticos'
                            }
                        }
                    },
                    id_cuenta: {
                        validators: {
                            notEmpty: { message: 'Este campo es obligatorio.' },
                        }
                    },
                    id_moneda: {
                        validators: {
                            notEmpty: { message: 'Este campo es obligatorio.' },
                        }
                    },
                    id_impuesto: {
                        validators: {
                            notEmpty: { message: 'Este campo es obligatorio.' },
                        }
                    },
                    tipo_item: {
                        validators: {
                            notEmpty: { message: 'Este campo es obligatorio.' },
                        }
                    },
                    monto_base: {
                        validators: {
                            notEmpty: { message: 'Este campo es obligatorio.' },
                            regexp: {
                                regexp: /^[0-9]+(\.[0-9]{1,2})+$/,
                                message: 'Solo puede contener valores numéricos'
                            }
                        }
                    },
                    comentario: {
                        validators: {
                            regexp: {
                                regexp: /^[a-zA-Z0-9/\s/Ñ/ñ/à/è/ì/ò/ù/À/È/Ì/Ò/Ù/á/é/í/ó/ú/ý/Á/É/Í/Ó/Ú/Ý/â/ê/î/ô/û/Â/Ê/Î/Ô/Û/ã/ñ/õ/Ã/Ñ/Õ/ä/ë/ï/ö/ü/ÿ/Ä/Ë/Ï/Ö/Ü/Ÿ/ç/Ç/,/./%/&/-/$]+$/,
                                message: 'Solo puede contener valores alfanuméricos'
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap(),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                    icon: new FormValidation.plugins.Icon({
                        valid: 'fa fa-check',
                        invalid: 'fa fa-times',
                        validating: 'fa fa-refresh',
                    })
                }
            }
        );
    }
});