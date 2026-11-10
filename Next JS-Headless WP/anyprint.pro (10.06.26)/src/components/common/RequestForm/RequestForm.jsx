import { useState, useEffect } from 'react'
import styles from './RequestForm.module.scss'
import Link from 'next/link';
import { useViewportWidthPixel } from '@/hooks/useViewportWidthPixel';
import { ClassicTxt } from '../ClassicTxt/ClassicTxt';

export const RequestForm = ({maxWidth, formId, modal, modalTitle, onModalChange}) => { 

    const { width } = useViewportWidthPixel();
    const [isModal, setModal] = useState(false);
    useEffect(() => {
        if (modal) setModal(true);
    }, [modal]);

    const [isChecked, setChecked] = useState(false);

    function adaptiveMinus(smallScreenValue, largeScreenValue, smallWidth = 360, largeWidth = maxWidth) {
        if (typeof window === 'undefined') return largeScreenValue;
        smallScreenValue = Number(smallScreenValue);
        largeScreenValue = Number(largeScreenValue);
        
        const addValue = largeScreenValue - smallScreenValue;
        const addWidth = largeWidth - smallWidth;
        const vw = window.innerWidth;

        return Math.round(
            Math.max(
                smallScreenValue,
                Math.min(
                    largeScreenValue,
                    smallScreenValue + addValue * ((vw - smallWidth) / addWidth)
                )
            )
        );
    }

    const nonModalStyle = {maxWidth: `${maxWidth}px`, padding: `45px ${adaptiveMinus(30, 50)}px 27px ${adaptiveMinus(30, 50)}px`};
    const modalStyle = {padding: `45px 0 27px 0`};

    const [formValues, setFormValues] = useState({
        name: '',
        email: '',
        tel: '',
        text: '', 
    });

    const [errors, setErrors] = useState({});

    const [isSending, setIsSending] = useState(false);

    const handleChange = (field) => (e) => {
        setFormValues(prev => ({ ...prev, [field]: e.target.value }));
       
        if (errors[field]) {
            setErrors(prev => {
            const next = { ...prev };
            delete next[field];
            return next;
            });
        }
    };

    const validate = () => {
        const newErrors = {};

        if (!formValues.name.trim()) {
            newErrors.name = true;
            setFormValues(prev => ({ ...prev, name: '' }));
        }

        if (!formValues.email.trim()) {
            newErrors.email = true;
            setFormValues(prev => ({ ...prev, email: '' }));
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formValues.email)) {
            newErrors.email = true;
            setFormValues(prev => ({ ...prev, email: '' }));
        }

        if (!formValues.tel.trim()) {
            newErrors.tel = true;
            setFormValues(prev => ({ ...prev, tel: '' }));
        } else if (!/^\+7\d{10}$/.test(formValues.tel)) {
            newErrors.tel = true;
            setFormValues(prev => ({ ...prev, tel: '' }));
        }

        if (!isChecked) newErrors.checkbox = true;

        setErrors(newErrors);
        return Object.keys(newErrors).length === 0;
    };

    const getPlaceholder = (field, defaultPlaceholder, errorText = 'Заполните это поле') => {
        return errors[field] ? errorText : defaultPlaceholder;
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (validate()) {
            setIsSending(true);
            try {
            const res = await fetch('/api/send-email', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formValues),
            });
            if (res.ok) {
                (() => {
                    onModalChange('formSuccessful');
                })();
                setFormValues({ name: '', email: '', tel: '', text: '' });
                setChecked(false);
            } else {
                (() => {
                    onModalChange('formError');
                })();
            }
            } catch {
                (() => {
                    onModalChange('formError');
                })();
            } finally {
                setIsSending(false);
            }
        }
    };

    return (
        <form id={formId} className={isModal ? styles['modal-request-from'] : styles['request-form']} style={modal ? modalStyle : nonModalStyle} onSubmit={handleSubmit} noValidate autoComplete='off'>
            {modal && <div className={styles['modal-txt']}><ClassicTxt text={modalTitle}/></div>}
            <label className={styles['name-label']}>
                <input type="text" className={`${styles['name']} ${errors.name ? styles['input-error'] : ''}`} placeholder={getPlaceholder('name', 'Ваше имя', 'Введите ваше имя')} value={formValues.name} onChange={handleChange('name')} name='name' autoComplete="off"/>
            </label>
            <label className={styles['name-email']}>
                <input type="email" className={`${styles['email']} ${errors.email ? styles['input-error'] : ''}`} placeholder={getPlaceholder('email', 'Адрес электронной почты', 'Укажите email')} value={formValues.email} onChange={handleChange('email')} name='email' autoComplete="off"/>
            </label>
            <label className={styles['name-tel']}>
                <input type="tel" className={`${styles['tel']} ${errors.tel ? styles['input-error'] : ''}`} placeholder={getPlaceholder('tel', 'Номер телефона', '+7ХХХХХХХХХХ')} value={formValues.tel} onChange={handleChange('tel')} name='tel' autoComplete="off"/>
            </label>
            <label className={styles['name-textarea']}>
                <textarea className={styles['textarea']} placeholder='Расскажите подробности о своём проекте' value={formValues.text} onChange={handleChange('text')} name="text"></textarea>
            </label>
            <button className={styles['submit-btn']} disabled={isSending}>Оставить заявку</button>
            <div className={styles['request-form-checkbox']}>
                <label className={styles['checkbox-label']}>
                    <input className={styles['checkbox']} type="checkbox" checked={isChecked} onChange={() => {}}/>
                    <div className={`${styles['fake-checkbox']} ${errors.checkbox ? styles['checkbox-error'] : ''}`} onClick={() => setChecked((prev) => !prev)}></div>
                    {isChecked && <div className={styles['fake-checkbox-condition']}></div>}
                </label>
                <div className={styles['request-form-policy']}>
                    Отправляя форму вы соглашаетесь <Link href={`#`} className={styles['request-form-policy-open-link']}>с Политикой конфеденциальности</Link>
                </div>
            </div>
        </form>
    )
}