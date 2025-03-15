SELECT
siccmp.id,
siccmp.id_seccion_ine_ciudadano,

cmc.cuerpo,
cmc.asunto,

sic.nombre_completo,
sic.nombre,
sic.apellido_paterno,
sic.apellido_materno,
sic.fecha_nacimiento,
sic.edad,
(SELECT sicr.nombre_completo FROM secciones_ine_ciudadanos sicr WHERE sicr.id = sic.id_seccion_ine_ciudadano_compartido ) relacionado,
sic.whatsapp,
sic.telefono,
sic.celular,
sic.correo_electronico,

(SELECT u.usuario FROM usuarios u WHERE u.id_seccion_ine_ciudadano = sic.id) usuario,
(SELECT u.password FROM usuarios u WHERE u.id_seccion_ine_ciudadano = sic.id) password,
(SELECT u.status FROM usuarios u WHERE u.id_seccion_ine_ciudadano = sic.id) status,
(SELECT e.estado FROM estados e WHERE e.id=sic.id_estado) estado,
(SELECT m.municipio FROM municipios m WHERE m.id=sic.id_estado) municipio,
(SELECT l.localidad FROM localidades l WHERE l.id=sic.id_localidad) localidad,
(SELECT dl.numero FROM distritos_locales dl WHERE dl.id=sic.id_distrito_local) distrito_local,
(SELECT df.numero FROM distritos_federales df WHERE df.id=sic.id_distrito_federal) distrito_federal,
(SELECT s.numero FROM secciones_ine s WHERE s.id=sic.id_seccion_ine) seccion,

cmr.servidor d_servidor,
cmr.puerto d_puerto,
cmr.cifrado d_cifrado,
cmr.usuario d_usuario,
cmr.password d_password,
cmr.de d_de,
cmr.correo_electronico u_correo_electronico,

siccmp.identificador u_identificador,
sic.codigo_seccion_ine_ciudadano u_codigo_unico


FROM secciones_ine_ciudadanos_campanas_mailing_programadas siccmp
LEFT JOIN secciones_ine_ciudadanos sic
ON sic.id = siccmp.id_seccion_ine_ciudadano
LEFT JOIN campanas_mailing cm
ON cm.id = siccmp.id_campana_mailing
LEFT JOIN correos_mailing cmr
ON cmr.id = cm.id_correo_mailing
LEFT JOIN campanas_mailing_cuerpos cmc
ON cmc.id_campana_mailing = cm.id

WHERE siccmp.status=0 AND siccmp.tipo=1 AND cm.status=1 AND cmr.status=1
LIMIT 100;