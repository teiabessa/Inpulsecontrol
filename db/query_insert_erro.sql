
-- -----------------------------------------------------
-- Data for table `control`.`Agenda`
-- -----------------------------------------------------
START TRANSACTION;
USE `control`;
INSERT INTO `control`.`Agenda` (`agenda_seq`, `agenda_sala`, `agenda_start_time`, `agenda_end_time`, `agenda_prof_seq`, `agenda_tyse_seq`, `agenda_secl_seq`, `agenda_outro_procedimento`, `agenda_concluded`, `agenda_tipo_pagto`, `agenda_client_seq`, `agenda_avulso`) VALUES (0, 1, '2015-03-02 08:00:00', '2015-03-02 09:00:00', 2, 1, NULL, NULL, NULL, 2, 1, NULL);
INSERT INTO `control`.`Agenda` (`agenda_seq`, `agenda_sala`, `agenda_start_time`, `agenda_end_time`, `agenda_prof_seq`, `agenda_tyse_seq`, `agenda_secl_seq`, `agenda_outro_procedimento`, `agenda_concluded`, `agenda_tipo_pagto`, `agenda_client_seq`, `agenda_avulso`) VALUES (1, 2, '2015-03-03 10:00:00', '2015-03-03 11:00:00', 3, 22, NULL, NULL, NULL, 1, 2, NULL);
INSERT INTO `control`.`Agenda` (`agenda_seq`, `agenda_sala`, `agenda_start_time`, `agenda_end_time`, `agenda_prof_seq`, `agenda_tyse_seq`, `agenda_secl_seq`, `agenda_outro_procedimento`, `agenda_concluded`, `agenda_tipo_pagto`, `agenda_client_seq`, `agenda_avulso`) VALUES (2, 3, '2015-03-04 13:00:00', '2015-03-04 14:00:00', 4, 22, NULL, NULL, NULL, NULL, 3, NULL);

COMMIT;

