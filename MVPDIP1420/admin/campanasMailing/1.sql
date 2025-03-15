INSERT INTO secciones_ine_ciudadanos_campanas_mailing_programadas
(id, id_seccion_ine_ciudadano, id_seccion_ine, id_distrito_local, id_distrito_federal, id_estado, id_municipio, id_campana_mailing, id_campana_mailing_cuerpo, id_campana_mailing_programada, status, fechaR, codigo_plataforma, codigo_seccion_ine_ciudadano, identificador, asunto, cuerpo, fecha_registro, hora_registro, fecha_hora_registro)
SELECT 
sic.id,
sic.id id_seccion_ine_ciudadano,
sic.id_seccion_ine,
sic.id_distrito_local,
sic.id_distrito_federal,
sic.id_estado,
sic.id_municipio,
/*sic.id_campana_mailing,*/
(SELECT cm.id from campanas_mailing cm limit 1) id_campana_mailing,
/*sic.id_campana_mailing_cuerpo,*/
(SELECT cmp.id from campanas_mailing_cuerpos cmp limit 1) id_campana_mailing_cuerpo,

NULL id_campana_mailing_programada,
'1' status,
sic.fechaR,
sic.codigo_plataforma,
sic.codigo_seccion_ine_ciudadano,
'1' identificador,
/*sic.asunto,*/
(SELECT cmp.asunto from campanas_mailing_cuerpos cmp limit 1) asunto,
/*sic.cuerpo,*/
(SELECT cmp.cuerpo from campanas_mailing_cuerpos cmp limit 1) cuerpo,
DATE(fechaR) fecha_registro,
TIME(fechaR) hora_registro,
fechaR fecha_hora_registro
FROM secciones_ine_ciudadanos sic;