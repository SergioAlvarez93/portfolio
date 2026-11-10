export const Burger = ({className="", onClick}) => {
    return (
        <div className={`${className}`} onClick={onClick}>
            <span></span>
        </div>
    )
}