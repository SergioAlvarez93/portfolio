import styles from './FormRequestMessage.module.scss'
import { ClassicGeneralTitle } from '../ClassicGeneralTitle/ClassicGeneralTitle';
import { ClassicTxt } from '../ClassicTxt/ClassicTxt';
import { ClassicModalWrap } from '../ClassicModalWrap/ClassicModalWrap';

export const FormRequestMessage = ({modalId, closeModal}) => {
    const title = modalId === 'formSuccessful' ? 'Заявка успешно оправлена!' : 'Ошибка отправки сообщения!';
    const text = modalId === 'formSuccessful' ? 'Наши менеджеры свяжутся с вами в ближайшее время. Благодарим за обращение.' : 'При отправке формы возникла ошибка, пожалуйста, сообщите нам об этом на почту info@anyprint.pro.'
    return (
        <ClassicModalWrap maxWidth='450' closeModal={closeModal}>
            <div className={styles['request-successful-title']}>
                <ClassicGeneralTitle title={title} />
            </div>
            <div className={styles['request-successful-text']}>
                <ClassicTxt text={text} />
            </div>
        </ClassicModalWrap>
    )
}