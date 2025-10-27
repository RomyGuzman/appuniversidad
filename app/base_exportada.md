[
{"type":"header","version":"5.2.1","comment":"Export to JSON plugin for PHPMyAdmin"},
{"type":"database","name":"appuniversidad"},
{"type":"table","name":"asistencia","database":"appuniversidad","data":
[
{"id":"1","estudiante_id":"1","materia_id":"1","fecha":"2023-10-01","estado":"Presente","observaciones":null,"inscripcion_id":"1"}
]
}
,{"type":"table","name":"carrera","database":"appuniversidad","data":
[
{"id":"1","nombre_carrera":"Desarrollo de Software","codigo_carrera":"DS-1"},
{"id":"2","nombre_carrera":"Profesorado de Inglés","codigo_carrera":"PI-1"}
]
}
,{"type":"table","name":"categoria","database":"appuniversidad","data":
[
{"id":"1","codigo_categoria":"CAT-TEC","nombre_categoria":"TECNICATURAS SUPERIORES","carrera_id":null},
{"id":"2","codigo_categoria":"CAT-PROF","nombre_categoria":"PROFESORADOS","carrera_id":null},
{"id":"3","codigo_categoria":"CAT-EDUC","nombre_categoria":"EDUCACIÓN","carrera_id":null},
{"id":"4","codigo_categoria":"CAT-SALUD","nombre_categoria":"SALUD","carrera_id":null},
{"id":"5","codigo_categoria":"CAT-TECNO","nombre_categoria":"TECNOLOGÍA","carrera_id":null}
]
}
,{"type":"table","name":"consultas_admin","database":"appuniversidad","data":
[

]
}
,{"type":"table","name":"estudiante","database":"appuniversidad","data":
[
{"id":"1","dni":"12345678","nombre_estudiante":"Juan Pérez","fecha_nacimiento":"2000-05-10","edad":"24","email":"juan@email.com","carrera_id":"1"},
{"id":"2","dni":"23456789","nombre_estudiante":"Lucía Fernández","fecha_nacimiento":"2001-08-22","edad":"23","email":"lucia@email.com","carrera_id":"2"},
{"id":"3","dni":"32052551","nombre_estudiante":"elias campos","fecha_nacimiento":"2000-06-25","edad":"43","email":"gfhryrtdfd@gmail.com","carrera_id":"1"}
]
}
,{"type":"table","name":"inscripcion","database":"appuniversidad","data":
[
{"id":"1","estudiante_id":"1","materia_id":"1","fecha_inscripcion":"2023-08-01","estado_inscripcion":"Confirmada","observaciones_inscripcion":null,"fecha_aprobacion":null,"cupo_asignado":"1"}
]
}
,{"type":"table","name":"materia","database":"appuniversidad","data":
[
{"id":"1","nombre_materia":"Programación I","codigo_materia":"PROG-101","carrera_id":"1"}
]
}
,{"type":"table","name":"modalidad","database":"appuniversidad","data":
[
{"id":"1","codigo_modalidad":"MOD-PRES","nombre_modalidad":"PRESENCIAL","carrera_id":null},
{"id":"2","codigo_modalidad":"MOD-SEMI","nombre_modalidad":"SEMIPRESENCIAL","carrera_id":null},
{"id":"3","codigo_modalidad":"MOD-VIRT","nombre_modalidad":"VIRTUAL","carrera_id":null},
{"id":"4","codigo_modalidad":"MOD-LIB","nombre_modalidad":"LIBRE","carrera_id":null}
]
}
,{"type":"table","name":"nota","database":"appuniversidad","data":
[
{"id":"1","estudiante_id":"1","materia_id":"1","calificacion":"8.50","fecha_evaluacion":"2023-10-15","observaciones":null,"inscripcion_id":"1"}
]
}
,{"type":"table","name":"profesor","database":"appuniversidad","data":
[
{"id":"1","legajo":"1001","nombre_profesor":"María García","carrera_id":"1"},
{"id":"2","legajo":"1002","nombre_profesor":"Carlos López","carrera_id":"2"}
]
}
,{"type":"table","name":"profesor_materia","database":"appuniversidad","data":
[
{"profesor_id":"1","materia_id":"1"}
]
}
,{"type":"table","name":"rol","database":"appuniversidad","data":
[
{"id":"1","nombre_rol":"admin"},
{"id":"3","nombre_rol":"alumno"},
{"id":"2","nombre_rol":"profesor"},
{"id":"4","nombre_rol":"Superadmin"}
]
}
,{"type":"table","name":"usuarios","database":"appuniversidad","data":
[
{"id":"1","usuario":"admin","password":"e10adc3949ba59abbe56e057f20f883e","fecha_registro":"2025-10-15 21:43:51","fecha_ultimo_ingreso":"2025-10-25 00:31:15","rol_id":"1","activo":"1"},
{"id":"2","usuario":"profesor1","password":"e10adc3949ba59abbe56e057f20f883e","fecha_registro":"2025-10-15 21:43:51","fecha_ultimo_ingreso":"2025-10-25 00:24:00","rol_id":"2","activo":"1"},
{"id":"3","usuario":"alumno1","password":"e10adc3949ba59abbe56e057f20f883e","fecha_registro":"2025-10-15 21:43:51","fecha_ultimo_ingreso":"2025-10-25 01:27:32","rol_id":"3","activo":"1"}
]
}
]
