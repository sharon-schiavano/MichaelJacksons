--
-- PostgreSQL database dump
--

-- Dumped from database version 16.4
-- Dumped by pg_dump version 16.4

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
-- Name: prodotti; Type: TABLE; Schema: public; Owner: www
--

CREATE TABLE public.prodotti (
    id integer NOT NULL,
    nome character varying(255) NOT NULL,
    descrizione text,
    prezzo numeric(10,2) NOT NULL,
    immagine character varying(255),
    tipo character varying(20) NOT NULL,
    CONSTRAINT prodotti_tipo_check CHECK (((tipo)::text = ANY (ARRAY[('prodotto'::character varying)::text, ('canzone'::character varying)::text])))
);


ALTER TABLE public.prodotti OWNER TO www;

--
-- Name: prodotti_id_seq; Type: SEQUENCE; Schema: public; Owner: www
--

CREATE SEQUENCE public.prodotti_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.prodotti_id_seq OWNER TO www;

--
-- Name: prodotti_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www
--

ALTER SEQUENCE public.prodotti_id_seq OWNED BY public.prodotti.id;


--
-- Name: utenti; Type: TABLE; Schema: public; Owner: www
--

CREATE TABLE public.utenti (
    id integer NOT NULL,
    username character varying(50) NOT NULL,
    email character varying(100) NOT NULL,
    password text NOT NULL,
    mjc integer DEFAULT 0,
    immagine_profilo character varying(255) DEFAULT '../uploads/profile_images/default.jpg'::character varying,
    data_iscrizione character varying(10) DEFAULT to_char((CURRENT_DATE)::timestamp with time zone, 'DD/MM/YYYY'::text),
    valuta integer DEFAULT 0,
    billie_jean integer DEFAULT 0,
    beat_it integer DEFAULT 0,
    rock_with_you integer DEFAULT 0,
    smooth_criminal integer DEFAULT 0,
    thriller integer DEFAULT 0,
    unlocked_billiejean boolean DEFAULT false,
    unlocked_beatit boolean DEFAULT false,
    unlocked_thriller boolean DEFAULT false,
    unlocked_smoothcriminal boolean DEFAULT false
);


ALTER TABLE public.utenti OWNER TO www;

--
-- Name: utenti_id_seq; Type: SEQUENCE; Schema: public; Owner: www
--

CREATE SEQUENCE public.utenti_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.utenti_id_seq OWNER TO www;

--
-- Name: utenti_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: www
--

ALTER SEQUENCE public.utenti_id_seq OWNED BY public.utenti.id;


--
-- Name: prodotti id; Type: DEFAULT; Schema: public; Owner: www
--

ALTER TABLE ONLY public.prodotti ALTER COLUMN id SET DEFAULT nextval('public.prodotti_id_seq'::regclass);


--
-- Name: utenti id; Type: DEFAULT; Schema: public; Owner: www
--

ALTER TABLE ONLY public.utenti ALTER COLUMN id SET DEFAULT nextval('public.utenti_id_seq'::regclass);


--
-- Data for Name: prodotti; Type: TABLE DATA; Schema: public; Owner: www
--

COPY public.prodotti (id, nome, descrizione, prezzo, immagine, tipo) FROM stdin;
1	Thriller (Album)	Album musicale Thriller.	15.00	../assets/images/shop/thriller.jpg	prodotto
2	Bad (Album)	Album musicale Bad.	12.00	../assets/images/shop/bad.jpg	prodotto
3	FunkoPop! MJ	FunkoPop! di Michael Jackson.	20.00	../assets/images/shop/funkomj1.png	prodotto
4	Maglietta MJ	T-shirt con immagine di Michael Jackson.	20.00	../assets/images/shop/mjtee.png	prodotto
5	Poster MJ	Poster di Michael Jackson.	10.00	../assets/images/shop/posterjackson.jpg	prodotto
7	Billie Jean	Canzone di Michael Jackson.	10.00	../assets/images/shop/billiejean.jpg	canzone
8	Beat It	Canzone di Michael Jackson.	6.00	../assets/images/shop/beatit.png	canzone
9	Smooth Criminal	Canzone di Michael Jackson.	5.00	../assets/images/shop/smoothcriminal.jpg	canzone
6	MJ The Experience Wii	Gioco Wii di Michael Jackson.	30.00	../assets/images/shop/jacksonwii.jpg	prodotto
10	Thriller	Canzone di Michael Jackson.	8.00	../assets/images/shop/thrillersong.jpg	canzone
\.


--
-- Data for Name: utenti; Type: TABLE DATA; Schema: public; Owner: www
--

COPY public.utenti (id, username, email, password, mjc, immagine_profilo, data_iscrizione, valuta, billie_jean, beat_it, rock_with_you, smooth_criminal, thriller, unlocked_billiejean, unlocked_beatit, unlocked_thriller, unlocked_smoothcriminal) FROM stdin;
37	mario	mario@gmail.com	$2y$10$i3g30sChPPc7lDWaKF1KOOkMyfEgw0aSWDL1x4IWFhJQZNrgQaCW6	0	../uploads/profile_images/default.jpg	23/02/2025	100	0	0	0	0	0	f	f	f	f
38	omar	omar@gmail.com	$2y$10$1nskGom1HUODXPTYcJp4S.Waqo/2grPJNAReU2XByUjcmWIZEV.Bi	0	../uploads/profile_images/default.jpg	23/02/2025	100	0	0	0	0	0	f	f	f	f
\.


--
-- Name: prodotti_id_seq; Type: SEQUENCE SET; Schema: public; Owner: www
--

SELECT pg_catalog.setval('public.prodotti_id_seq', 10, true);


--
-- Name: utenti_id_seq; Type: SEQUENCE SET; Schema: public; Owner: www
--

SELECT pg_catalog.setval('public.utenti_id_seq', 38, true);


--
-- Name: prodotti prodotti_pkey; Type: CONSTRAINT; Schema: public; Owner: www
--

ALTER TABLE ONLY public.prodotti
    ADD CONSTRAINT prodotti_pkey PRIMARY KEY (id);


--
-- Name: utenti utenti_email_key; Type: CONSTRAINT; Schema: public; Owner: www
--

ALTER TABLE ONLY public.utenti
    ADD CONSTRAINT utenti_email_key UNIQUE (email);


--
-- Name: utenti utenti_pkey; Type: CONSTRAINT; Schema: public; Owner: www
--

ALTER TABLE ONLY public.utenti
    ADD CONSTRAINT utenti_pkey PRIMARY KEY (id);


--
-- Name: utenti utenti_username_key; Type: CONSTRAINT; Schema: public; Owner: www
--

ALTER TABLE ONLY public.utenti
    ADD CONSTRAINT utenti_username_key UNIQUE (username);


--
-- Name: utenti trigger_aggiorna_valuta; Type: TRIGGER; Schema: public; Owner: www
--

CREATE TRIGGER trigger_aggiorna_valuta BEFORE UPDATE ON public.utenti FOR EACH ROW WHEN ((old.mjc IS DISTINCT FROM new.mjc)) EXECUTE FUNCTION public.aggiorna_valuta();


--
-- PostgreSQL database dump complete
--

