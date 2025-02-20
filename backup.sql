--
-- PostgreSQL database dump
--

-- Dumped from database version 16.5
-- Dumped by pg_dump version 16.5

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: aggiorna_valuta(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.aggiorna_valuta() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    incremento INTEGER;
BEGIN
    incremento := NEW.mjc - OLD.mjc;
    IF incremento != 0 THEN
        NEW.valuta := OLD.valuta + incremento;
    END IF;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.aggiorna_valuta() OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: utenti; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.utenti (
    id integer NOT NULL,
    username character varying(50) NOT NULL,
    email character varying(100) NOT NULL,
    password text NOT NULL,
    mjc integer DEFAULT 0,
    immagine_profilo character varying(255) DEFAULT '../uploads/default.jpg'::character varying,
    data_iscrizione character varying(10) DEFAULT to_char((CURRENT_DATE)::timestamp with time zone, 'DD/MM/YYYY'::text),
    valuta integer DEFAULT 0,
    billie_jean integer DEFAULT 0,
    beat_it integer DEFAULT 0,
    rock_with_you integer DEFAULT 0,
    smooth_criminal integer DEFAULT 0,
    thriller integer DEFAULT 0,
    unlocked_billiejean boolean DEFAULT false,
    unlocked_beatit boolean DEFAULT false,
    unlocked_smoothcriminal boolean DEFAULT false,
    unlocked_thriller boolean DEFAULT false
);


ALTER TABLE public.utenti OWNER TO postgres;

--
-- Name: utenti_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.utenti_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.utenti_id_seq OWNER TO postgres;

--
-- Name: utenti_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.utenti_id_seq OWNED BY public.utenti.id;


--
-- Name: utenti id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utenti ALTER COLUMN id SET DEFAULT nextval('public.utenti_id_seq'::regclass);


--
-- Data for Name: utenti; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.utenti (id, username, email, password, mjc, immagine_profilo, data_iscrizione, valuta, billie_jean, beat_it, rock_with_you, smooth_criminal, thriller, unlocked_billiejean, unlocked_beatit, unlocked_smoothcriminal, unlocked_thriller) FROM stdin;
17	rocco	hunt@gmail.com	$2y$10$v91oHG6c04qSwaI6DFUQvexWcGmmIZLiud7McW6bJNfiAIxYmSrfC	1	../uploads/default_profile.png	13/02/2025	1	60625	64175	20250	0	0	f	f	f	f
11	ciccio	pasticcio@gmail.com	$2y$10$Y7iBS7vg4lA/Yclcvp6nlO8PsDShU8BSsMfucBWewOzkqwbuSwfqW	0	../uploads/default_profile.png	11/02/2025	0	0	0	0	0	0	f	f	f	f
8	gonzalo higuain	jojo@gmail.com	$2y$10$XDFx2FDGN2YlU4237Prl.uDShy7bNflSo7MZD0Bhspd/2YgyrTbny	1	https://b.fssta.com/uploads/application/soccer/headshots/1500.vresize.350.350.medium.8.png	09/02/2025	0	10	0	0	0	0	f	f	f	f
6	michael jordan	sesso@gmail.com	$2y$10$5.S.GMOK8j5rwh1ZxOo.leb1udd7Ks82yFit0HTUMOBT6FK1IHkFy	2	https://www.proballers.com/media/cache/resize_600_png/https---www.proballers.com/ul/player/backup/1-1ef7f627-301a-6be4-a79b-ab34b8448102.png	09/02/2025	0	2	0	35	0	2	f	f	f	f
16	domysex	revenge@gmail.com	$2y$10$3lNASixMnyPqCMvVZ/lETeRXzcnQZzx.k71m.dqnctSdT1BU5tOXe	0	../uploads/default_profile.png	13/02/2025	0	0	0	0	0	0	f	f	f	f
18	elnino	corr@gmail.com	$2y$10$dB4Ncf1VbCzl4Nj.H45IL.zdC7l.mxKetd21FGwOpb84dAAOoKclK	303	../uploads/default_profile.png	16/02/2025	303	0	0	56700	0	1000000	f	t	f	t
7	maurizio costanzo	odioInegri@gmail.com	$2y$10$fJprEFLgpW0y/yM3IR5uZeftEV0wjueH9XfYHWdatXXDYvR3nYUm.	30	https://upload.wikimedia.org/wikipedia/commons/e/ec/Costanzobau_%28cropped%29.jpg	09/02/2025	0	0	7	0	0	0	f	f	t	f
5	domenico	ciao@gmail.com	$2y$10$oR6ypeCUdrCsAA1NOjImsuk4JSFHb1SIdfWFZ4W7jIImVJJl2vGg6	76	../uploads/67ab78c5c674e.jpg	09/02/2025	21	0	70900	0	39675	94800	f	t	f	t
\.


--
-- Name: utenti_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.utenti_id_seq', 18, true);


--
-- Name: utenti utenti_email_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utenti
    ADD CONSTRAINT utenti_email_key UNIQUE (email);


--
-- Name: utenti utenti_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utenti
    ADD CONSTRAINT utenti_pkey PRIMARY KEY (id);


--
-- Name: utenti utenti_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.utenti
    ADD CONSTRAINT utenti_username_key UNIQUE (username);


--
-- Name: utenti trigger_aggiorna_valuta; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER trigger_aggiorna_valuta BEFORE UPDATE ON public.utenti FOR EACH ROW WHEN ((old.mjc IS DISTINCT FROM new.mjc)) EXECUTE FUNCTION public.aggiorna_valuta();


--
-- PostgreSQL database dump complete
--

