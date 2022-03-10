SELECT per.per_id, est.est_id, per.per_pri_nombre, per.per_pri_apellido,
per.per_seg_apellido, per.per_cedula, per.per_correo, per.per_fecha_creacion
FROM db_asgard.persona per
INNER JOIN db_academico.estudiante est on est.per_id = per.per_id
where per_cedula in (
'0927185926',
'0924745946',
'0916886203',
'0926880899',
'0705375715',
'1203842503',
'0923735617',
'1803705035',
'0604868943',
'0706563285',
'305100486',
'30382098',
'30604478',
'28150033',
'27737874',
'28047638',
'20273277',
'0952064939',
'24494593',
'25817435',
'27667399',
'29623738',
'4412004991',
'1290999104',
'801199918',
'0930757745',
'0950724021',
'0929157568',
'0923501209',
'060063953',
'0961599313',
'1204854697'
);