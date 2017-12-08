DROP TABLE IF EXISTS DISPOSITIVO;

DROP TABLE IF EXISTS HABITACION;

DROP TABLE IF EXISTS LOG;

DROP TABLE IF EXISTS MONITOR;

DROP TABLE IF EXISTS SISTEMA;

DROP TABLE IF EXISTS TIPO;

/*==============================================================*/
/* Table: DISPOSITIVO                                           */
/*==============================================================*/
CREATE TABLE DISPOSITIVO
(
   DIS_ID               INT(11) NOT NULL,
   HAB_ID               INT(11) DEFAULT NULL,
   TIPO_ID              INT(11) DEFAULT NULL,
   DIS_NOM              VARCHAR(30),
   DIS_TOP              VARCHAR(50),
   DIS_COL              VARCHAR(10),
   DIS_EST              VARCHAR(50),
   PRIMARY KEY (DIS_ID)
)
ENGINE=INNODB DEFAULT CHARSET=UTF8 COLLATE=UTF8_BIN;

/*==============================================================*/
/* Table: HABITACION                                            */
/*==============================================================*/
CREATE TABLE HABITACION
(
   HAB_ID               INT(11) NOT NULL,
   SIS_ID               INT(11) DEFAULT NULL,
   HAB_NOM              VARCHAR(30),
   PRIMARY KEY (HAB_ID)
)
ENGINE=INNODB DEFAULT CHARSET=UTF8 COLLATE=UTF8_BIN;

/*==============================================================*/
/* Table: LOG                                                   */
/*==============================================================*/
CREATE TABLE LOG
(
   LOG_ID               INT(11) NOT NULL,
   DIS_ID               INT(11) DEFAULT NULL,
   LOG_VAL              VARCHAR(10),
   LOG_FEC              DATE DEFAULT NULL,
   PRIMARY KEY (LOG_ID)
)
ENGINE=INNODB DEFAULT CHARSET=UTF8 COLLATE=UTF8_BIN;

/*==============================================================*/
/* Table: MONITOR                                               */
/*==============================================================*/
CREATE TABLE MONITOR
(
   MON_ID               INT(11) NOT NULL,
   SIS_ID               INT(11) DEFAULT NULL,
   MON_DIS_IN           INT(11) DEFAULT NULL,
   MON_VAL_IN           VARCHAR(15),
   MON_COM              VARCHAR(5),
   MON_DIS_OUT          INT(11) DEFAULT NULL,
   MON_VAL_OUT          VARCHAR(15),
   MON_MAIL             TINYINT(1) DEFAULT NULL,
   PRIMARY KEY (MON_ID)
)
ENGINE=INNODB DEFAULT CHARSET=UTF8 COLLATE=UTF8_BIN;

/*==============================================================*/
/* Table: SISTEMA                                               */
/*==============================================================*/
CREATE TABLE SISTEMA
(
   SIS_ID               INT(11) NOT NULL,
   SIS_NOM              VARCHAR(30),
   SIS_IP               VARCHAR(20),
   SIS_PORT             INT(11) DEFAULT NULL,
   SIS_PAT              VARCHAR(10),
   SIS_USU              VARCHAR(40),
   SIS_PAS              VARCHAR(40),
   SIS_SSL              TINYINT(1) DEFAULT NULL,
   SIS_KEEP             INT(11) DEFAULT NULL,
   SIS_OUT              INT(11) DEFAULT NULL,
   SIS_CLID             VARCHAR(20),
   SIS_CLN              TINYINT(1) DEFAULT NULL,
   SIS_LWT              VARCHAR(50),
   SIS_LWP              VARCHAR(10),
   SIS_QOS              INT(11) DEFAULT NULL,
   SIS_RET              TINYINT(1) DEFAULT NULL,
   SIS_URL              VARCHAR(100),
   SIS_COV              INT(11) DEFAULT NULL,
   SIS_DEF              TINYINT(1) DEFAULT NULL,
   SIS_MAIL1            VARCHAR(50),
   SIS_MAIL2            VARCHAR(50),
   PRIMARY KEY (SIS_ID)
)
ENGINE=INNODB DEFAULT CHARSET=UTF8 COLLATE=UTF8_BIN;

INSERT INTO `SISTEMA` (`SIS_ID`, `SIS_NOM`, `SIS_IP`, `SIS_PORT`, `SIS_PAT`, `SIS_USU`, `SIS_PAS`, `SIS_SSL`, `SIS_KEEP`, `SIS_OUT`, `SIS_CLID`, `SIS_CLN`, `SIS_LWT`, `SIS_LWP`, `SIS_QOS`, `SIS_RET`, `SIS_URL`, `SIS_COV`, `SIS_DEF`, `SIS_MAIL1`, `SIS_MAIL2`) VALUES
(1, 'Eclipse', 'iot.eclipse.org', 80, '/ws', '', '', 0, 120, 5, '', 1, '', '', 0, 0, '', 300, 1, '', '');

/*==============================================================*/
/* Table: TIPO                                                  */
/*==============================================================*/
CREATE TABLE TIPO
(
   TIPO_ID              INT(11) NOT NULL,
   TIPO_NOM             VARCHAR(15),
   PRIMARY KEY (TIPO_ID)
)
ENGINE=INNODB DEFAULT CHARSET=UTF8 COLLATE=UTF8_BIN;

ALTER TABLE DISPOSITIVO ADD CONSTRAINT FK_REFERENCE_1 FOREIGN KEY (HAB_ID)
      REFERENCES HABITACION (HAB_ID);

ALTER TABLE HABITACION ADD CONSTRAINT FK_REFERENCE_4 FOREIGN KEY (SIS_ID)
      REFERENCES SISTEMA (SIS_ID);

ALTER TABLE LOG ADD CONSTRAINT FK_REFERENCE_3 FOREIGN KEY (DIS_ID)
      REFERENCES DISPOSITIVO (DIS_ID);

ALTER TABLE MONITOR ADD CONSTRAINT FK_REFERENCE_5 FOREIGN KEY (SIS_ID)
      REFERENCES SISTEMA (SIS_ID) ON DELETE CASCADE ON UPDATE CASCADE;
